<?php namespace Websemantics\EntityBuilderExtension\Anomaly\Addon\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Websemantics\EntityBuilderExtension\Anomaly\Addon\Console\Command\ScaffoldModule;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Anomaly\Streams\Platform\Addon\Console\Command\MakeAddonPaths;
use Websemantics\EntityBuilderExtension\Traits\JobsDispatcher;

/**
 * Class MakeAddon.
 *
 * Override core MakeAddon command to generate module files that are compatible with Entity Builder.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
 class MakeAddon extends \Anomaly\Streams\Platform\Addon\Console\MakeAddon
{
    use DispatchesJobs, JobsDispatcher {
       JobsDispatcher::dispatch insteadof DispatchesJobs;
    }

    /**
     * Execute the console command.
     */
    public function fire(AddonManager $addons)
    {   dd('Die Here');
        list($vendor, $type, $slug, $path) =
            ebxResolveAddonNamespace($this->argument('namespace'), $this->option('shared'));

        if ($type === 'module') {
            $this->dispatch(new ScaffoldModule($vendor, $type, $slug, $path));
        }

        parent::fire($addons);
    }
}
