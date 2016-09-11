<?php namespace Websemantics\BuilderExtension\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Websemantics\BuilderExtension\Traits\Registry;
use Websemantics\BuilderExtension\Traits\IgnoreJobs;
use Websemantics\BuilderExtension\Command\ScaffoldModule;

/**
 * Class MakeAddon.
 *
 * Override core MakeAddon command to generate module files that are
 * compatible with Builder extension.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
 class MakeAddon extends \Anomaly\Streams\Platform\Addon\Console\MakeAddon
{
    use Registry;
    use DispatchesJobs, IgnoreJobs {
       IgnoreJobs::dispatch insteadof DispatchesJobs;
    }

    /**
     * Execute the console command.
     */
    public function fire(AddonManager $addons)
    {
        list($vendor, $type, $slug, $path) =
            _resolveAddonNamespace($this->argument('namespace'), $this->option('shared'));

        if ($type === 'module') {

          /* Get the default module template from the registry */

          if($this->download(_config('config.default-module'))){
            // $this->logo();

              /*
                read the Builder template metadata and return the templaet context object
              */
              // $context = $this->getContext($this->getTemplateMetadata($template));

              // $this->dispatch(new ScaffoldModule($vendor, $type, $slug, $path, $this->getBuilderPath('default-module/template/module'), []));
          } else {
            // $this->ignore = false;
            parent::fire($addons);
          }
        } else {
          parent::fire($addons);
        }
        parent::fire($addons);
    }
}
