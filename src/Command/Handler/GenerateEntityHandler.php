<?php namespace Websemantics\EntityBuilderExtension\Command\Handler;

use Websemantics\EntityBuilderExtension\Command\Traits\FileProcessor;
use Websemantics\EntityBuilderExtension\Command\GenerateEntity;
use Websemantics\EntityBuilderExtension\Filesystem\Filesystem;
use Websemantics\EntityBuilderExtension\Parser\EntityNameParser;
use Websemantics\EntityBuilderExtension\Parser\ModuleNameParser;
use Websemantics\EntityBuilderExtension\Parser\VendorNameParser;
use Websemantics\EntityBuilderExtension\Parser\NamespaceParser;
use Websemantics\EntityBuilderExtension\Parser\GenericPhpParser;
use Websemantics\EntityBuilderExtension\Parser\SeedersParser;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Support\Parser;

/**
 * Class GenerateEntityHandler
 *
 * This handles 'StreamWasCreated' event
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @package   Websemantics\EntityBuilderExtension
 */

class GenerateEntityHandler
{
    use  FileProcessor;

    /**
     * Create a new GenerateEntityHandler instance.
     *
     * @param Filesystem  $files
     * @param Parser      $parser
     * @param Application $application
     */
    function __construct(Filesystem $files, Parser $parser)
    {
        $this->setFiles($files);
        $this->setParser($parser);
    }

    /**
     * Handle the command.
     *
     * @param GenerateEntity $command
     */
    public function handle(GenerateEntity $command)
    { 
        $stream = $command->getStream();
        $module = $command->getModule();

        $entity   = __DIR__ . '/../../../resources/assets/entity';

        $namespace_folder = ebxGetNamespaceFolderTemplate($module);

        $this->files->setAvoidOverwrite(ebxGetAvoidOverwrite($module));

        $data = $this->getTemplateData($module, $stream);

        $destination = $module->getPath();

        $this->files->parseDirectory($entity."/code/$namespace_folder" , 
                                     $destination.'/src', $data);
        try {
                                
            $this->processFile(
                $destination . '/src/' . $data['module_name'] . 'ModuleServiceProvider.php',
                ['routes' => $entity.'/templates/module/routes.php',
                 'bindings' => $entity.'/templates/module/bindings.php',
                 'singletons' => $entity.'/templates/module/singletons.php'], $data);

            $this->processFile(
                $destination . '/src/' . $data['module_name'] . 'Module.php',
                ['sections' => $entity.'/templates/module/sections.php'], $data);

            $this->processFile(
                $destination . '/resources/lang/en/addon.php',
                ['section' => $entity.'/templates/module/addon.php'], $data);

            $this->processFile(
                $destination . '/src/' . $data['module_name'] . 'ModuleSeeder.php',
                ['seeders' => $entity.'/templates/module/seeding.php'], $data);

        } catch (\PhpParser\Error $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get the template data from a stream object.
     *
     * @param  Module $module
     * @param  StreamInterface $stream
     * @return array
     */
    protected function getTemplateData(Module $module, StreamInterface $stream)
    {
        $entityName = (new EntityNameParser())->parse($stream);
        $moduleName = (new ModuleNameParser())->parse($module);
        $namespace  = (new NamespaceParser())->parse($stream);

        // Wheather we use a grouping folder for all streams with the same namespace
        $namespace_folder = ebxGetNamespaceFolder($module,$namespace);

        return [
            'docblock'                      => ebxGetDocblock($module),
            'namespace'                     => $namespace,
            'seeder_data'                   => (new SeedersParser())->parse($module, $stream),
            'namespace_folder'              => $namespace_folder,
            'vendor_name'                   => (new VendorNameParser())->parse($module),
            'module_name'                   => $moduleName,
            'module_name_lower'             => strtolower($moduleName),
            'stream_slug'                   => studly_case($stream->getSlug()),
            'entity_name'                   => $entityName,
            'entity_name_plural'            => str_plural($entityName),
            'entity_name_lower'             => strtolower($entityName),
            'entity_name_lower_plural'      => strtolower(str_plural($entityName)),
            'extends_repository'            => ebxExtendsRepository($module),
            'extends_repository_use'        => ebxExtendsRepositoryUse($module),

        ];
    }
}



