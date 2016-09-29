<?php namespace Websemantics\BuilderExtension\Command;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Assignment\AssignmentModel;
use Websemantics\BuilderExtension\Traits\TemplateProcessor;
use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class ModifyEntity. Generates code for assignements
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class ModifyEntity
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
        $this->setFiles(app('Websemantics\BuilderExtension\Filesystem\Filesystem'));
        $this->setParser(app('Websemantics\BuilderExtension\Support\TwigParser'));
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
        $entity = __DIR__.'/../../resources/stubs/entity';
        $source = $entity.'/code/{namespace}/';

        /* get the field config params from build.php */
        $fieldConfig = _getFieldConfig(
            $module,
            $stream->getNamespace(),
            $assignment->getFieldSlug()
        );

        /* set a list of files to avoid overwrite */
        $this->files->setAvoidOverwrite(_getAvoidOverwrite($module));

        /* get the template data */
        $data = $this->getTemplateData($module, $stream, $assignment, $fieldConfig);

        /* get the namespace destination folder, if any! */
        $namespaceFolder = _getNamespaceFolder($module, $data['namespace'], true);

        $entityDest = $destination.'/src/'.$namespaceFolder.$data['entity_name'];

        /* get the assigned class name, i.e. TextFieldType */
        $fieldTypeClassName = _getFieldTypeClassName($assignment);

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

        $fieldSlug = $assignment->getFieldSlug();
        $fieldLabel = ucwords(str_replace('_',' ', $assignment->getFieldSlug()));
        $namespace = studly_case($stream->getNamespace());

        /* wheather we use a grouping folder for all streams with the same namespace */
        $namespaceFolder = _getNamespaceFolder($module, $namespace);

        return [
            'namespace' => $namespace,
            'namespace_folder' => $namespaceFolder,
            'vendor_name' => studly_case($module->getVendor()),
            'module_name' => studly_case($module->getSlug()),
            'entity_name' => studly_case(str_singular($stream->getSlug())),
            'field_slug' => $fieldSlug,
            'field_label' => $fieldLabel,
            'relation_name' => camel_case($fieldSlug),
            'null_relationship_entry' => _nullRelationshipEntry($module),
            'column_template' => $fieldConfig['column_template'],
        ];
    }
}
