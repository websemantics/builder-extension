<?php namespace Websemantics\BuilderExtension\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Websemantics\BuilderExtension\Traits\TemplateProcessor;
use Websemantics\BuilderExtension\Parser\EntityNameParser;
use Websemantics\BuilderExtension\Parser\ModuleNameParser;
use Websemantics\BuilderExtension\Parser\VendorNameParser;
use Websemantics\BuilderExtension\Parser\NamespaceParser;
use Websemantics\BuilderExtension\Parser\SeedersParser;
use Websemantics\BuilderExtension\Parser\EntityLabelParser;
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
        $this->setParser(app('Anomaly\Streams\Platform\Support\Parser'));
    }

    /**
     * Handle the command.
     *
     */
    public function handle()
    {
        $stream = $this->stream;
        $module = $this->module;

        $entityPath = __DIR__.'/../../resources/assets/entity';
        $modulePath = __DIR__.'/../../resources/assets/module';

        $namespace_folder = ebxGetNamespaceFolderTemplate($module);

        $data = $this->getTemplateData($module, $stream);

        /* uncomment the array entries to protect these files from being overwriten or add your own */
        $this->files->setAvoidOverwrite(ebxGetAvoidOverwrite($module, [
              // $data['module_name'] . 'ModuleSeeder.php',
              // $data['module_name'] . 'Module.php',
              // $data['module_name'] . 'ModuleServiceProvider.php',
            ]));

        $dest = $module->getPath();

        /* initially, copy the template files to the entity's src folder */
        $this->files->parseDirectory(
            $entityPath."/code/$namespace_folder",
            $dest.'/src',
            $data
        );

        /* create an empty seeder if it does not exist */
        $this->put($dest . '/resources/seeders/' . $data['entity_name_lower']. '.php', '', true);

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
                [$data['entity_name_lower_plural'] => $entityPath.'/templates/module/section.php'],
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
        $entityName = (new EntityNameParser())->parse($stream);
        $entityLabel = (new EntityLabelParser())->parse($stream);
        $moduleName = (new ModuleNameParser())->parse($module);
        $namespace = (new NamespaceParser())->parse($stream);
        $vendorName = (new VendorNameParser())->parse($module);

        /* check if we are using a grouping folder for all generated entities with the same namespace */
        $namespace_folder = ebxGetNamespaceFolder($module, $namespace);

        return [
            'docblock' => ebxGetDocblock($module),
            'namespace' => $namespace,
            'seeder_data' => (new SeedersParser())->parse($module, $stream),
            'namespace_folder' => $namespace_folder,
            'vendor_name' => $vendorName,
            'vendor_name_lower' => strtolower($vendorName),
            'module_name' => $moduleName,
            'module_name_lower' => strtolower($moduleName),
            'stream_slug' => $stream->getSlug(),
            'studly_case_stream_slug' => studly_case($stream->getSlug()),
            'entity_label' => $entityLabel,
            'entity_label_plural' => str_plural($entityLabel),
            'entity_name' => $entityName,
            'entity_name_plural' => str_plural($entityName),
            'entity_name_lower' => strtolower($entityName),
            'entity_name_lower_plural' => strtolower(str_plural($entityName)),
            'extends_repository' => ebxExtendsRepository($module),
            'extends_repository_use' => ebxExtendsRepositoryUse($module),

        ];
    }
}
