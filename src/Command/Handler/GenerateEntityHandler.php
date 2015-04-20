<?php namespace Websemantics\EntityBuilderExtension\Command\Handler;

use Websemantics\EntityBuilderExtension\Command\GenerateEntity;
use Websemantics\EntityBuilderExtension\Filesystem\Filesystem;
use Websemantics\EntityBuilderExtension\Parser\EntityNameParser;
use Websemantics\EntityBuilderExtension\Parser\ModuleNameParser;
use Websemantics\EntityBuilderExtension\Parser\VendorNameParser;
use Websemantics\EntityBuilderExtension\Parser\NamespaceParser;
use Websemantics\EntityBuilderExtension\Parser\StreamAssignmentsParser;
use Websemantics\EntityBuilderExtension\Parser\GenericPhpParser;
use Websemantics\EntityBuilderExtension\Parser\TableFieldsParser;
use Websemantics\EntityBuilderExtension\Parser\FormFieldsParser;
use Websemantics\EntityBuilderExtension\Parser\ModelContentParser;
use Websemantics\EntityBuilderExtension\Parser\RepositoryContentParser;
use Websemantics\EntityBuilderExtension\Parser\SeederDataParser;

use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Support\Parser;

/**
 * Class GenerateEntityHandler
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @package   Websemantics\EntityBuilderExtension
 */

class GenerateEntityHandler
{

    /**
     * The file system utility.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The parser utility.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * Create a new GenerateEntityHandler instance.
     *
     * @param Filesystem  $files
     * @param Parser      $parser
     * @param Application $application
     */
    function __construct(Filesystem $files, Parser $parser)
    {
        $this->files       = $files;
        $this->parser       = $parser;
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

        $namespace_folder = array_get($module->hasConfig('builder'), 
                            'namespace_folder', true) ? "" : "{namespace}/";

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
     * Process a php target file  to append PHP syntax-sensitive content 
     * from multiple template sources.
     *
     * @param  string $file, a php file to modify
     * @param  array  $templates list of key (property name), value (template file)
     * @param  string $data used to replace placeholders inside all template files
     */
    protected function processFile($file, $templates, $data)
    {
        $content = file_get_contents($file);

        $phpParser = new GenericPhpParser($content, $data, $this->parser);

        foreach ($templates as $property => $template) {
           $phpParser->parse($property, $template);
        }
        
        $content = $phpParser->prettyPrint();

        if(!is_null($content))
            file_put_contents($file, $content);
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
        $namespace_folder = array_get($module->hasConfig('builder'), 
                            'namespace_folder', true) ? "$namespace\\" : "";

        return [
            'docblock'                      => array_get($module->hasConfig('builder'), 'docblock', ''),
            'namespace'                     => $namespace,
            'table_fields'                  => (new TableFieldsParser())->parse($module, $stream),
            'form_fields'                   => (new FormFieldsParser())->parse($module,$stream),
            'model_content'                 => (new ModelContentParser())->parse($module, $stream),
            'repository_content'            => (new RepositoryContentParser())->parse($module, $stream),
            'seeder_data'                   => (new SeederDataParser())->parse($module, $stream),
            'namespace_folder'              => $namespace_folder,
            'vendor_name'                   => (new VendorNameParser())->parse($module),
            'module_name'                   => $moduleName,
            'module_name_lower'             => strtolower($moduleName),
            'entity_name'                   => $entityName,
            'entity_name_plural'            => str_plural($entityName),
            'entity_name_lower'             => strtolower($entityName),
            'entity_name_lower_plural'      => strtolower(str_plural($entityName))
        ];
    }
}



