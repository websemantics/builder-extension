<?php namespace Websemantics\BuilderExtension;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class BuilderExtensionServiceProvider
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */
class BuilderExtensionServiceProvider extends AddonServiceProvider
{

    /**
     * The commands.
     *
     * @var array
     */
    protected $commands = [
      'Anomaly\Streams\Platform\Addon\Console\MakeAddon' => 'Websemantics\BuilderExtension\Console\MakeAddon',
      'Anomaly\Streams\Platform\Stream\Console\Make' => 'Websemantics\BuilderExtension\Console\Make',
      'Websemantics\BuilderExtension\Console\ListTemplates' => 'Websemantics\BuilderExtension\Console\ListTemplates',
      'Websemantics\BuilderExtension\Console\ClearTemplates' => 'Websemantics\BuilderExtension\Console\ClearTemplates'
    ];

    /**
     * The event listeners.
     *
     * @var array
     */
    protected $listeners = [
      'Anomaly\Streams\Platform\Stream\Event\StreamWasCreated'         => ['Websemantics\BuilderExtension\Handler\StreamWasCreatedHandler'],
      'Anomaly\Streams\Platform\Assignment\Event\AssignmentWasCreated' => ['Websemantics\BuilderExtension\Handler\AssignmentWasCreatedHandler'],
      'Anomaly\Streams\Platform\Addon\Module\Event\ModuleWasInstalled' => ['Websemantics\BuilderExtension\Handler\ModuleWasInstalledHandler']
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
      'migration.creator' => 'Websemantics\BuilderExtension\Database\Migration\MigrationCreator',
      'Illuminate\Filesystem\Filesystem' => 'Websemantics\BuilderExtension\Filesystem\Filesystem'
    ];
}
