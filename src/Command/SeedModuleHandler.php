<?php

namespace Websemantics\EntityBuilderExtension\Command;

use Websemantics\EntityBuilderExtension\Command\SeedModule;
use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class SeedModuleHandler.
 *
 * This handles 'ModuleWasInstalled' event
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 *
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 */
class SeedModuleHandler
{
    /**
     * Handle the command.
     *
     * Add a default Module route, language entries etc per Module
     *
     * @param SeedModule $command
     */
    public function handle(SeedModule $command)
    {
        $module = $command->getModule();

        \Artisan::call(
            'db:seed',
            [
                '--addon' => $module->getNamespace(),
                '--force' => true,
            ]
        );
    }
}
