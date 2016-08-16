<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Anomaly\Streams\Platform\Assignment\AssignmentModel;

/**
 * Class AssignmentNameParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class AssignmentNameParser
{

    /**
     * Return the module name.
     *
     * @param  Module $module
     * @return string
     */
    public function parse(AssignmentModel $assignment)
    {
        return studly_case($assignment->getFieldSlug());
    }
}