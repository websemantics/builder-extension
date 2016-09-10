<?php namespace Websemantics\BuilderExtension\Console;

use Github\Client;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Websemantics\BuilderExtension\Traits\Spinner;
use Anomaly\Streams\Platform\Application\Application;
use Websemantics\BuilderExtension\Filesystem\Filesystem;

/**
 */

class Registry extends Command
{
    use DispatchesJobs;

    /*
    * This might be useful in the future for multi-threading
    */
    use Spinner;

    /**
     * The zip archive
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
     * The Github registery organization
     *
     * @var string
     */
    protected $registry;

    /**
     * The number of minutes to cache api calls
     *
     * @var null|false|int
     */
    protected $ttl = false;

    /**
     * The application instance
     *
     * @var Application
     */
    protected $application;

    /**
     * The file system
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct(Application $application, Filesystem $files, Client $client)
    {
      parent::__construct();

      /* Check for ZipArchive */
      if (!class_exists('ZipArchive'))
          throw new Exception('Error, ZipArchive class is not avilable');

      $this->zip = new \ZipArchive();
      $this->client = $client;
      $this->files = $files;
      $this->application = $application;
      $this->registry = bxConfig('config.registry');
      $this->ttl = bxConfig('config.ttl');
    }

    /**
     * Print a block.
     *
     * @param string $message, user message
     * @param string $color, text color
     * @param string $background, background color
     * @return void
     */
    protected function block($message, $color = 'yellow', $background = 'black')
    {
      $this->output->block($message, null, "fg=$color;bg=$background");
    }

    /**
     * Get cache key
     *
     * @return string
     */
    protected function getCacheKey($key)
    {
        return md5("builder_extension_$key");
    }

    /**
     * Get the builder public path (create if it doesn't exist)
     *
     * @param string path,
     * @return string
     */
    protected function getBuilderPath($path = '')
    {
        $path = $this->application->getStoragePath(bxConfig('config.path') . ($path?"/$path":""));

        /*
          make sure the parent folder is a directory or vice versa (parent directory is a folder)
        */
        if (!$this->files->isDirectory($parent = dirname($path))) {
            $this->files->makeDirectory($parent, 0777, true);
        }

        return $path;
    }

    /**
     * Flush cache and delete all templates
     *
     * @return void
     */
    public function flush($key)
    {
      app('cache')->forget($key);
    }

    /**
     * Print ascii logo
     *
     * @return void
     */
    public function logo()
    {
      $this->block(bxView('ascii.logo')->render(), 'yellow');
    }

    /**
     * Download a Builder template from the registery,
     *
     * @param string $template, the selected template
     * @return boolean
     */
    public function download($template)
    {
      $dist = $this->getBuilderPath($template);
      $src = bxRender(bxConfig('config.archive'), [
                          'registry' => $this->registry,
                          'template' => $template]);

      /* get a temp folder to download the template zip to */
      $tmp = $this->getBuilderPath(bxConfig('config.tmp'));

      try {
        /* download the template zip file, uncompress and remove */
        $this->files->put($tmp, file_get_contents($src));
        $this->zip->open($tmp);
        $this->zip->extractTo($dist);
        $this->files->deleteDirectory(dirname($tmp));
      } catch (\Github\Exception\RuntimeException $e) {
        $this->error('Builder template not found');
        return false;
      }
      return true;
    }
}
