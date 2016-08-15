<?php namespace Websemantics\EntityBuilderExtension\Command;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Assignment\AssignmentModel;
use Illuminate\Contracts\Bus\SelfHandling;
use Websemantics\EntityBuilderExtension\Traits\TemplateProcessor;
use Anomaly\Streams\Platform\Addon\Module\Module;
use Websemantics\EntityBuilderExtension\Parser\EntityNameParser;
use Websemantics\EntityBuilderExtension\Parser\ModuleNameParser;
use Websemantics\EntityBuilderExtension\Parser\VendorNameParser;
use Websemantics\EntityBuilderExtension\Parser\NamespaceParser;
use Websemantics\EntityBuilderExtension\Parser\AssignmentSlugParser;
use Websemantics\EntityBuilderExtension\Parser\AssignmentLabelParser;

/**
 * Class ModifyEntity. Generates code for assignements
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class ModifyEntity implements SelfHandling
{
  use TemplateProcessor;

    /**
     * The assignment.
     *
     * @var \Anomaly\Streams\Platform\Assignment\AssignmentModel
     */
    protected $assignment;

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
     * Create a new ModifyEntity instance.
     *
     * @param Module $module
     * @param StreamInterface $stream
     * @param AssignmentModel  $assignment
     * @param Filesystem  $files
     * @param Parser      $parser
     */
    public function __construct(Module $module,
                                StreamInterface $stream,
                                AssignmentModel $assignment)
    {
        $this->module = $module;
        $this->stream = $stream;
        $this->assignment = $assignment;
        $this->setFiles(app('Websemantics\EntityBuilderExtension\Filesystem\Filesystem'));
        $this->setParser(app('Anomaly\Streams\Platform\Support\Parser'));
    }

    /**
     * Handle the command.
     *
     */
    public function handle()
    {
        $module = $this->module;
        $stream = $this->stream;
        $assignment = $this->assignment;
        $destination = $module->getPath();
        $entity = __DIR__.'/../../resources/assets/entity';
        $source = $entity.'/code/{namespace}/';

        /* get the field config params from build.php */
        $fieldConfig = ebxGetFieldConfig(
            $module,
            $stream->getNamespace(),
            $assignment->getFieldSlug()
        );

        /* set a list of files to avoid overwrite */
        $this->files->setAvoidOverwrite(ebxGetAvoidOverwrite($module));

        /* get the template data */
        $data = $this->getTemplateData($module, $stream, $assignment, $fieldConfig);

        /* get the namespace destination folder, if any! */
        $namespaceFolder = ebxGetNamespaceFolder($module, $data['namespace'], true);

        $entityDest = $destination.'/src/'.$namespaceFolder.$data['entity_name'];

        /* get the assigned class name, i.e. TextFieldType */
        $fieldTypeClassName = ebxGetFieldTypeClassName($assignment);

        /* (1) process the form builder class */
        if (!$fieldConfig['hide_field']) {
            $this->processFormBuilder(
                $entityDest.'/Form/'. $data['entity_name'].'FormBuilder.php',
                $entity.'/templates/field/form/', $fieldTypeClassName, $data
            );
        }

        /* (2) process the table column class */
        if (!$fieldConfig['hide_column']) {
            $this->processTableColumns(
                $entityDest.'/Table/'. $data['entity_name'].'TableColumns.php',
                $entity.'/templates/field/table/' . ($data['column_template'] ? 'template/' : ''),
                $fieldTypeClassName, $data
            );
        }

        /* (3) process the field language file */
        $this->processFile(
            $destination.'/resources/lang/en/field.php',
            [$data['field_slug'] => $entity.'/templates/module/field.php'],
            $data
        );
    }

    /**
     * process the form builder and add fields to it.
     *
     * @param string $file, a php file to modify
     * @param string $path, path to the templates folder
     * @param string $ftClsName field type class name
     * @param string $data used to replace placeholders inside all template files
     */
    protected function processFormBuilder($file, $path, $ftClsName, $data)
    {
        $this->processTemplate($file, $path."$ftClsName.txt", $data,
        'protected $fields = [', '];', $path."TextFieldType.txt");
    }

    /**
     * process the table columns and add fields to it.
     *
     * @param string $file, a php file to modify
     * @param string $path, path to the templates folder
     * @param string $ftClsName field type class name
     * @param string $data, used to replace placeholders inside all template files
     */
    protected function processTableColumns($file, $path, $ftClsName, $data)
    {
        $this->processTemplate($file, $path."$ftClsName.txt", $data,
        '$builder->setColumns([', ']);', $path."TextFieldType.txt");
    }

    /**
     * Get the template data from a stream object.
     *
     * @param Module          $module
     * @param StreamInterface $stream
     * @param AssignmentModel $assignment
     * @param array           $fieldConfig
     *
     * @return array
     */
    protected function getTemplateData(
        Module $module,
        StreamInterface $stream,
        AssignmentModel $assignment,
        $fieldConfig
    ) {

        $entityName = (new EntityNameParser())->parse($stream);
        $moduleName = (new ModuleNameParser())->parse($module);
        $fieldSlug = (new AssignmentSlugParser())->parse($assignment);
        $fieldLabel = (new AssignmentLabelParser())->parse($assignment);
        $namespace = (new NamespaceParser())->parse($stream);

        /* wheather we use a grouping folder for all streams with the same namespace */
        $namespaceFolder = ebxGetNamespaceFolder($module, $namespace);

        return [
            'namespace' => $namespace,
            'namespace_folder' => $namespaceFolder,
            'vendor_name' => (new VendorNameParser())->parse($module),
            'module_name' => $moduleName,
            'entity_name' => $entityName,
            'field_slug' => $fieldSlug,
            'field_label' => $fieldLabel,
            'relation_name' => camel_case($fieldSlug),
            'null_relationship_entry' => ebxNullRelationshipEntry($module),
            'column_template' => $fieldConfig['column_template'],
        ];
    }
}
