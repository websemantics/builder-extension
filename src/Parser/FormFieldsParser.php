<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class FormFieldsParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class FormFieldsParser
{

    /**
     * Return the entity name.
     *
     * @param  StreamInterface $stream
     * @param  Module $module
     * @return string
     */
    public function parse(Module $module, StreamInterface $stream)
    {
        // First, check if the user has default content
        $destination = $module->getPath();
        $entityName  = studly_case(str_singular($stream->getSlug()));
        $file        = $destination."/builder/$entityName/$entityName"."FormFields.php";

        $fields = file_exists($file) ? file_get_contents($file) : '';

        if($fields == ''){
            $assignments = $stream->getAssignments();
            foreach ($assignments as $assignment) {
                $fields .= '"'.$assignment->getFieldSlug().'",
                ';
            }
        }
        
        return $fields;
    }
}