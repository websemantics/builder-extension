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
        $source = $entity.'/code/{{namespace|studly_case}}/';

        /* get the field config params from build.php */
        $fieldConfig = _getFieldConfig(
            $module,
            $stream->getNamespace(),
            $assignment->getFieldSlug()
        );

        /* protect module classes from being overwriten */
        $this->files->setAvoidOverwrite(_config('builder.avoid_overwrite', $module));

        /* get the template data */
        $data = [
            'config' => _config('builder', $module),
            'field_slug' => $assignment->getFieldSlug(),
            'vendor' => $module->getVendor(),
            'module_slug' => $module->getSlug(),
            'namespace' => $stream->getNamespace(),
            'stream_slug' => $stream->getSlug(),
            'entity_name' => studly_case(str_singular($stream->getSlug())),
            'column_template' => $fieldConfig['column_template'],
        ];

        $entityDest = $destination.'/src/'.
        (_config('builder.group', $module) ? $data['namespace'].'/' : '') .
        $data['entity_name'];

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
        $this->processFile($destination.'/resources/lang/en/field.php',
            [$data['field_slug'] => $entity.'/templates/module/field.php'], $data);
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
}
