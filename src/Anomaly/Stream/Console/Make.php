<?php

namespace Websemantics\EntityBuilderExtension\Anomaly\Stream\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Websemantics\EntityBuilderExtension\Traits\JobsDispatcher;

/**
 * Class Make.
 *
 * Override core Stream Make command to avoid conflic with Entity Builder.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
class Make extends \Anomaly\Streams\Platform\Stream\Console\Make
{
    use DispatchesJobs, JobsDispatcher {
       JobsDispatcher::dispatch insteadof DispatchesJobs;
    }
}
