<?php

namespace Websemantics\BuilderExtension\Traits;

use Github\Client;
use Anomaly\Streams\Platform\Application\Application;
use Websemantics\BuilderExtension\Filesystem\Filesystem;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Class Registry.
 *
 * Use a Github organization as the Builder registry.
 *
 * TODO: Seperate cache, console output from registery and generic
 *       command operations. This class is quite the mess and should
 *       have never been seen by another human being, but here it is!
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 *
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc
 */
trait Registry
{
    use Spinner;

    /**
     * The zip archive.
     *
     * @var ZipArchive
     */
    protected $zip;

    /**
     * The Github api client.
     *
     * @var Github\Client
     */
    protected $client;

    /**
     * The Github registery organization.
     *
     * @var string
     */
    protected $registry;

    /**
     * The number of minutes to cache api calls.
     *
     * @var null|false|int
     */
    protected $ttl = false;

    /**
     * The application instance.
     *
     * @var Application
     */
    protected $application;

    /**
     * The file system.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new console command instance.
     */
    public function __construct(Application $application, Filesystem $files, Client $client)
    {
        parent::__construct();

      /* Check for ZipArchive */
      if (!class_exists('ZipArchive')) {
          throw new Exception('Error, ZipArchive class is not avilable, unable to recover!');
      }

        $this->zip = new \ZipArchive();
        $this->client = $client;
        $this->files = $files;
        $this->application = $application;
        $this->registry = _config('config.registry');
        $this->ttl = _config('config.ttl');
    }

    /**
     * Get cache key.
     *
     * @return string
     */
    protected function getCacheKey($key)
    {
        return md5("builder_extension_$key");
    }

    /**
     * Get the Builder storage path (create if it doesn't exist).
     *
     * @param string path,
     *
     * @return string
     */
    protected function getBuilderPath($path = '')
    {
        $path = $this->application->getStoragePath(_config('config.path').($path ? "/$path" : ''));

        /* make sure the parent folder is a directory or parent directory is a folder */
        if (!$this->files->isDirectory($parent = dirname($path))) {
            $this->files->makeDirectory($parent, 0777, true);
        }

        return $path;
    }

    /**
     * Flush cache and delete all templates.
     */
    protected function flush($key)
    {
        app('cache')->forget($key);
    }

    /**
     * Print Builder ascii logo
     */
    protected function logo()
    {
        /* Get some theme going */
        $this->output->getFormatter()->setStyle('p', new OutputFormatterStyle('magenta', 'black'));
        $this->output->getFormatter()->setStyle('t', new OutputFormatterStyle('white', 'black'));
        $this->output->writeln(_view('ascii.logo')->render());
    }

    /**
     * Print a block.
     *
     * @param string $message,    user message
     * @param string $color,      text color
     * @param string $background, background color
     */
    protected function block($message, $color = 'yellow', $background = 'black')
    {
        $this->output->block($message, null, "fg=$color;bg=$background");
    }

    /**
     * Download a Builder template from the registery,.
     *
     * @param string $template, the selected template
     * @param bool   $force,    force download if the template already exists
     *
     * @return bool (true = success)
     */
    protected function download($template, $force = false)
    {
        $dist = $this->getBuilderPath();
        $path = "$dist/$template";

        if (!$this->files->exists($path) || $force) {
            $bar = $this->createProgressIndicator();
            $src = _render(_config('config.archive'), [
                            'registry' => $this->registry,
                            'template' => $template, ]);

        /* get a temp folder to download the template zip to */
        $tmp = $this->getBuilderPath(_config('config.tmp'));

            try {
                /* download the template zip file, show progress, uncompress and remove */
          $bar->start(" Downloading '$template' ... ");

                $this->files->put($tmp, file_get_contents($src, false, stream_context_create([],
          ['notification' => function ($notification_code) use ($bar) {
              if (in_array($notification_code, [STREAM_NOTIFY_CONNECT, STREAM_NOTIFY_PROGRESS])) {
                  $bar->advance();
              }
          }])));
                $this->zip->open($tmp);
                $this->zip->extractTo($dist);
                $this->zip->close();
                $this->files->moveDirectory("$path-master", $path, true);
                $this->files->deleteDirectory(dirname($tmp));
                $bar->finish(" Download '$template' was successful                               ");
            } catch (\ErrorException $e) {
                return false;
            }
        } else {
            $this->output->note("Builder template '$template' already exists. \nUse --force option to get a fresh copy.");
        }

        return true;
    }

    /**
     * Load the template metadata.
     *
     * @param string $template, the Builder template
     *
     * @return array
     */
    protected function getTemplateMetadata($template)
    {
        if ($this->files->exists($path = $this->getBuilderPath("$template/builder.json"))) {
            return json_decode($this->files->get($path), true);
        }

        return [];
    }

    /**
     * Get the template type (module, field_type, extension etc).
     *
     * @param string $template, the Builder template name
     *
     * @return string
     */
    protected function getTemplateType($template)
    {
        $parts = explode('-', $template);
        return array_pop($parts);
    }

    /**
     * Parse Builder template schema, interact with the user and return the context object,.
     *
     * @param string $template, the Builder template name
     * @param string $defults,  list of defults (override the schema defults)
     * @param string $ignore,   if true, ignore asking a question for the provided defults
     *
     * @return array
     */
    protected function getTemplateContext($template = [], $defults = [], $ignore = false)
    {
        $context = [];
        $metadata = $this->getTemplateMetadata($template);

        foreach (isset($metadata['schema']) ? $metadata['schema'] : [] as $property => $schema) {
            $question = !empty($schema['label']) ? $schema['label'] : $property;
            $default = isset($defults[$property]) ? $defults[$property] :
                  (isset($schema['default']) ? $schema['default'] : null);
            $context[$property] = ($ignore && isset($defults[$property])) ? $default :
                               $this->ask($question.'?', $default);
        }

        /* Add the addon type */
        $context['type'] = $this->getTemplateType($template);

        return $context;
    }
}
