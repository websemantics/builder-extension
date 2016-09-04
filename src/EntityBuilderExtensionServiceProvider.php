<?php namespace Websemantics\EntityBuilderExtension;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class EntityBuilderExtensionServiceProvider
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */
class EntityBuilderExtensionServiceProvider extends AddonServiceProvider
{

    /**
     * The commands.
     *
     * @var array
     */
    protected $commands = [
      'Anomaly\Streams\Platform\Addon\Console\MakeAddon' => 'Websemantics\EntityBuilderExtension\Anomaly\Addon\Console\MakeAddon',
      'Anomaly\Streams\Platform\Stream\Console\Make' => 'Websemantics\EntityBuilderExtension\Anomaly\Stream\Console\Make'
    ];

    /**
     * The event listeners.
     *
     * @var array
     */
    protected $listeners = [
      'Anomaly\Streams\Platform\Stream\Event\StreamWasCreated'         => ['Websemantics\EntityBuilderExtension\Handler\StreamWasCreatedHandler'],
      'Anomaly\Streams\Platform\Assignment\Event\AssignmentWasCreated' => ['Websemantics\EntityBuilderExtension\Handler\AssignmentWasCreatedHandler'],
      'Anomaly\Streams\Platform\Addon\Module\Event\ModuleWasInstalled' => ['Websemantics\EntityBuilderExtension\Handler\ModuleWasInstalledHandler']
    ];

    /**
     * The class bindings.
     *
     * @var array
     */
    protected $bindings = [
    ];

    /**
     * The singleton bindings.
     *
     * @var array
     */
    protected $singletons = [
      'Illuminate\Filesystem\Filesystem' => 'Websemantics\EntityBuilderExtension\Filesystem\Filesystem'
    ];
}
