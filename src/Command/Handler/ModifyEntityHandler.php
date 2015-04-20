<?php namespace Websemantics\EntityBuilderExtension\Command\Handler;

use Websemantics\EntityBuilderExtension\Command\ModifyEntity;
use Websemantics\EntityBuilderExtension\Filesystem\Filesystem;
use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Assignment\AssignmentModel;
use Anomaly\Streams\Platform\Support\Parser;
use Websemantics\EntityBuilderExtension\Parser\EntityNameParser;
use Websemantics\EntityBuilderExtension\Parser\ModuleNameParser;
use Websemantics\EntityBuilderExtension\Parser\VendorNameParser;
use Websemantics\EntityBuilderExtension\Parser\NamespaceParser;
use Websemantics\EntityBuilderExtension\Parser\AssignmentNameParser;
use Websemantics\EntityBuilderExtension\Parser\AssignmentSlugParser;
use Websemantics\EntityBuilderExtension\Parser\StreamAssignmentsParser;


/**
 * Class ModifyEntityHandler
 *
 * Here we will only handle Fields creations
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @package   Websemantics\EntityBuilderExtension
 */

class ModifyEntityHandler
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
     * Create a new ModifyEntityHandler instance.
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
     * @param ModifyEntity $command
     */
    public function handle(ModifyEntity $command)
    { 

        $stream     = $command->getStream();
        $module     = $command->getModule();
        $assignment = $command->getAssignment();

        $destination = $module->getPath();

        $entity   = __DIR__ . '/../../../resources/assets/entity';

        $data = $this->getTemplateData($module, $stream, $assignment);

        $source = $entity.'/code/{namespace}/';

        $destination = $module->getPath() . '/src/' . $data['namespace_folder'];

        // (1) Process the form builder class
        $this->processFormBuilder($destination . $data['entity_name'] . '/Form/'. 
                               $data['entity_name'].'FormBuilder.php',
                               $entity.'/templates/field/form_simple_field.txt', $data);
        
        // (2) Process the table column class
        $this->processTableColumns($destination . $data['entity_name'] . '/Table/'. 
                               $data['entity_name'].'TableColumns.php',
                               $entity.'/templates/field/table_simple_field.txt', $data);
    }

    /**
     * process the form builder and add fields to it
     *
     * @param  string $file, a php file to modify
     * @param  string  $templates file location
     * @param  string $data used to replace placeholders inside all template files
     */
    protected function processFormBuilder($file, $template, $data)
    {

        // Use the simple field template
        $template = $this->parser->parse(file_get_contents($template), $data);

        $content = file_get_contents($file);

        $needle = 'protected $fields = [';

        $content = substr_replace($content, $template, strpos($content, $needle), 
                                  strlen($needle));

        file_put_contents($file, $content);
    }

    /**
     * process the table columns and add fields to it
     *
     * @param  string $file, a php file to modify
     * @param  string  $templates file location
     * @param  string $data used to replace placeholders inside all template files
     */
    protected function processTableColumns($file, $template, $data)
    {

        // Use the simple field template
        $template = $this->parser->parse(file_get_contents($template), $data);

        $content = file_get_contents($file);

        $needle = '$builder->setColumns([';

        $content = substr_replace($content, $template, strpos($content, $needle), 
                                  strlen($needle));

        file_put_contents($file, $content);
    }

    /**
     * Get the template data from a stream object.
     *
     * @param  Module $module
     * @param  StreamInterface $stream
     * @return array
     */
    protected function getTemplateData(Module $module, StreamInterface $stream, 
                                       AssignmentModel $assignment)
    {
        $entityName     = (new EntityNameParser())->parse($stream);
        $moduleName     = (new ModuleNameParser())->parse($module);
        $fieldSlug      = (new AssignmentSlugParser())->parse($assignment);
        $namespace      = (new NamespaceParser())->parse($stream);

        // Wheather we use a grouping folder for all streams with the same namespace
        $namespace_folder = array_get($module->hasConfig('builder'), 
                            'namespace_folder', true) ? "$namespace/" : "";
        
        return [
            'namespace'                     => $namespace,
            'namespace_folder'              => $namespace_folder,
            'vendor_name'                   => (new VendorNameParser())->parse($module),
            'module_name'                   => $moduleName,
            'entity_name'                   => $entityName,
            'field_slug'                    => $fieldSlug
        ];
    }
}



