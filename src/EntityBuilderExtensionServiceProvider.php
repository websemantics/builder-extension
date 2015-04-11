<?php namespace Websemantics\EntityBuilderExtension;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Illuminate\Routing\Router;

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
    /**
     * Addon event listeners.
     *
     * @var array
     */
    protected $listeners = [
        'Anomaly\Streams\Platform\Stream\Event\StreamWasCreated' => ['Websemantics\EntityBuilderExtension\Handlers\Events\StreamWasCreatedHandler'],
        'Anomaly\Streams\Platform\Assignment\Event\AssignmentWasCreated' => ['Websemantics\EntityBuilderExtension\Handlers\Events\AssignmentWasCreatedHandler']
    ];

}


