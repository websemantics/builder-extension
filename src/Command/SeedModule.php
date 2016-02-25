<?php namespace Websemantics\EntityBuilderExtension\Command;

use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class SeedModule
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class SeedModule
{

    /**
     * The module class.
     *
     * @var \Anomaly\Streams\Platform\Addon\Module\Module
     */
    protected $module;

    /**
     * Create a new SeedModule instance.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Get the module instance.
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }
}
