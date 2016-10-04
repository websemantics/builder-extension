<?php namespace Websemantics\BuilderExtension\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Websemantics\BuilderExtension\Traits\TemplateProcessor;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;

/**
 * Class GenerateEntity.
 *
 * Generates entity classes from a stream schema
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
        $dest = $module->getPath();

        /* seed file path for this entity */
        $seedFile = "$dest/resources/seeders/".strtolower(str_singular($stream->getSlug())).".php";

        $data = [
            'config' => _config('builder', $module),
            'vendor' => $module->getVendor(),
            'namespace' => $stream->getNamespace(),
            'module_slug' => $module->getSlug(),
            'stream_slug' => $stream->getSlug(),
            'entity_name' => studly_case(str_singular($stream->getSlug())),
            'seeder_data' => file_exists($seedFile) ? file_get_contents($seedFile) : ''
        ];
        $moduleName = studly_case($data['module_slug']);

        /* protect module classes from being overwriten */
        $this->files->setAvoidOverwrite(_config('builder.avoid_overwrite', $module));

        /* initially, copy the entity template files to the module src folder */
        if(_config('builder.group', $module)){
          $this->files->parseDirectory($entityPath."/code/", "$dest/src", $data);
        } else {
          $this->files->parseDirectory($entityPath."/code/{{namespace|studly_case}}/", "$dest/src", $data);
          $this->files->parseDirectory($entityPath."/code/Http", "$dest/src/Http", $data);
        }

        /* create an empty seeder if it does not exist */
        $this->put("$dest/resources/seeders/" . strtolower($data['entity_name']). '.php', '', true);

        try {
            /* stitch the entity with the module classes */
            $this->processFile("$dest/src/$moduleName".'ModuleServiceProvider.php',
                ['routes' => $entityPath.'/templates/module/routes.php',
                 'bindings' => $entityPath.'/templates/module/bindings.php',
                 'singletons' => $entityPath.'/templates/module/singletons.php'], $data);

            $this->processFile("$dest/src/$moduleName".'Module.php',
                ['sections' => $entityPath.'/templates/module/sections.php'], $data);

            $this->processFile("$dest/src/$moduleName".'ModuleSeeder.php',
                ['seeders' => $entityPath.'/templates/module/seeding.php'], $data );

            $this->processFile("$dest/resources/lang/en/section.php",
                [strtolower(str_plural($data['entity_name'])) => $entityPath.'/templates/module/section.php'], $data);

            $this->processFile("$dest/resources/config/permissions.php",
                [$data['stream_slug'] => $entityPath.'/templates/module/permissions.php'], $data);

            $this->processFile(
                "$dest/resources/lang/en/stream.php",
                [$data['stream_slug'] => $entityPath.'/templates/module/stream.php'], $data);

            $this->processFile(
                "$dest/resources/lang/en/permission.php",
                [$data['stream_slug'] => $entityPath.'/templates/module/permission.php'], $data);



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
}
