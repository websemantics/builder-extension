<?php namespace Websemantics\BuilderExtension\Parser;

use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class ModuleNameParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class ModuleNameParser
{

    /**
     * Return the module name.
     *
     * @param  Module $module
     * @return string
     */
    public function parse(Module $module)
    {
        return studly_case($module->getSlug());
    }
}