<?php namespace Websemantics\BuilderExtension\Parser;

use Anomaly\Streams\Platform\Assignment\AssignmentModel;

/**
 * Class AssignmentLabelParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class AssignmentLabelParser
{

    /**
     * Return the module name.
     *
     * @param  Module $module
     * @return string
     */
    public function parse(AssignmentModel $assignment)
    {
        return ucwords(str_replace('_',' ', $assignment->getFieldSlug()));
    }

}



