<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class VendorNameParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class VendorNameParser
{

    /**
     * Return the vendor name.
     *
     * @param  Module $module
     * @return string
     */
    public function parse(Module $module)
    {
        return studly_case($module->getVendor());
    }
}