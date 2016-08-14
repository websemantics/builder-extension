<?php namespace Websemantics\EntityBuilderExtension;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class EntityBuilderExtensionServiceProvider
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */
class EntityBuilderExtensionServiceProvider extends AddonServiceProvider
{
    protected $commands = array('Websemantics\EntityBuilderExtension\Console\ModuleCreate');

    protected $singletons = ['Illuminate\Filesystem\Filesystem' => 'Websemantics\EntityBuilderExtension\Filesystem\Filesystem'];

    protected $listeners = [
        'Anomaly\Streams\Platform\Stream\Event\StreamWasCreated'         => ['Websemantics\EntityBuilderExtension\Handlers\Events\StreamWasCreatedHandler'],
        'Anomaly\Streams\Platform\Assignment\Event\AssignmentWasCreated' => ['Websemantics\EntityBuilderExtension\Handlers\Events\AssignmentWasCreatedHandler'],
        'Anomaly\Streams\Platform\Addon\Module\Event\ModuleWasInstalled' => ['Websemantics\EntityBuilderExtension\Handlers\Events\ModuleWasInstalledHandler']
    ];

}
