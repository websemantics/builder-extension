<?php namespace Websemantics\EntityBuilderExtension\Command;

use Illuminate\Contracts\Bus\SelfHandling;
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

class SeedModule implements SelfHandling
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
     * @param Filesystem  $files
     * @param Parser      $parser
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Handle the command.
     *
     * Add a default Module route, language entries etc per Module
     *
     */
    public function handle()
    {
        \Artisan::call(
            'db:seed',
            [
                '--addon' => $this->module->getNamespace(),
                '--force' => true,
            ]
        );
    }
}
