<?php namespace Websemantics\BuilderExtension\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Websemantics\BuilderExtension\Traits\TemplateProcessor;
use Websemantics\BuilderExtension\Parser\SeedersParser;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;

/**
 * Class GenerateEntity. Generates code from a stream schema
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class GenerateEntity
{
  use TemplateProcessor;

    /**
     * The stream interface.
     *
     * @var \Anomaly\Streams\Platform\Stream\Contract\StreamInterface
     */
    protected $stream;

    /**
     * The module class.
     *
     * @var \Anomaly\Streams\Platform\Addon\Module\Module
     */
    protected $module;

    /**
     * Create a new GenerateEntity instance.
     *
     * @param Module $module
     * @param StreamInterface $stream
     */
    public function __construct(Module $module, StreamInterface $stream)
    {
        $this->stream = $stream;
        $this->module = $module;
        $this->setFiles(app('Websemantics\BuilderExtension\Filesystem\Filesystem'));
        $this->setParser(app('Websemantics\BuilderExtension\Support\TwigParser'));
    }

    /**
     * Handle the command.
     *
     */
    public function handle()
    {
        $stream = $this->stream;
        $module = $this->module;

        $entityPath = __DIR__.'/../../resources/stubs/entity';
        $modulePath = __DIR__.'/../../resources/stubs/module';

        $data = $this->getTemplateData($module, $stream);

        /* uncomment the array entries to protect these files from being overwriten or add your own */
        $this->files->setAvoidOverwrite(_getAvoidOverwrite($module, [
              // $data['module_name'] . 'ModuleSeeder.php',
              // $data['module_name'] . 'Module.php',
              // $data['module_name'] . 'ModuleServiceProvider.php',
            ]));

        $dest = $module->getPath();

        /* initially, copy the template files to the entity's src folder */
        if(config($module->getNamespace('builder.namespace_folder'))){
          $this->files->parseDirectory($entityPath."/code/", $dest.'/src', $data);
        } else {
          $this->files->parseDirectory($entityPath."/code/{{namespace}}/", $dest.'/src', $data);
          $this->files->parseDirectory($entityPath."/code/Http", $dest.'/src/Http', $data);
        }

        /* create an empty seeder if it does not exist */
        $this->put($dest . '/resources/seeders/' . strtolower($data['entity_name']). '.php', '', true);

        try {

            /* secondly, stitch the entity with the module classes */
            $this->processFile(
                $dest.'/src/'.$data['module_name'].'ModuleServiceProvider.php',
                ['routes' => $entityPath.'/templates/module/routes.php',
                 'bindings' => $entityPath.'/templates/module/bindings.php',
                 'singletons' => $entityPath.'/templates/module/singletons.php'],
                $data
            );

            $this->processFile(
                $dest.'/src/'.$data['module_name'].'Module.php',
                ['sections' => $entityPath.'/templates/module/sections.php'],
                $data
            );

            $this->processFile(
                $dest.'/src/'.$data['module_name'].'ModuleSeeder.php',
                ['seeders' => $entityPath.'/templates/module/seeding.php'],
                $data
            );

            $this->processFile(
                $dest.'/resources/lang/en/section.php',
                [str_plural(strtolower($data['entity_name'])) => $entityPath.'/templates/module/section.php'],
                $data
            );

            $this->processFile(
                $dest.'/resources/lang/en/stream.php',
                [$data['stream_slug'] => $entityPath.'/templates/module/stream.php'],
                $data
            );
        } catch (\PhpParser\Error $e) {
            die($e->getMessage());
        }
    }

    /**
     * process a language file.
     *
     * @param string $file,     a php file to modify
     * @param string $templates file location
     * @param string $data      used to replace placeholders inside all template files
     */
    protected function processLanguage($file, $template, $data)
    {
        $this->processTemplate($file, $template, $data, 'return [', '];');
    }

    /**
     * Get the template data from a stream object.
     *
     * @param Module          $module
     * @param StreamInterface $stream
     *
     * @return array
     */
    protected function getTemplateData(Module $module, StreamInterface $stream)
    {
        return [
            'config' => config($module->getNamespace('builder')),
            'namespace' => studly_case($stream->getNamespace()),
            'seeder_data' => (new SeedersParser())->parse($module, $stream),
            'vendor_name' => studly_case($module->getVendor()),
            'module_name' => studly_case($module->getSlug()),
            'stream_slug' => $stream->getSlug(),
            'entity_name' => studly_case(str_singular($stream->getSlug())),
            'entity_label' => ucwords(str_replace('_',' ', $stream->getSlug()))

        ];
    }
}


  // 'field_slug' => $fieldSlug,
  // 'column_template' => $fieldConfig['column_template'],
  // 'namespace' => $namespace,
  // 'seeder_data' => $seeder_data,
  // 'vendor_name' => studly_case($module->getVendor()),
  // 'module_name' => studly_case($module->getSlug()),
  // 'stream_slug' => $stream->getSlug(),
  // 'entity_label' => ucwords(str_replace('_',' ', $stream->getSlug())),
  // 'entity_name' => studly_case(str_singular($stream->getSlug())),
