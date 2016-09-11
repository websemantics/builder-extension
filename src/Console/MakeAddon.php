<?php namespace Websemantics\BuilderExtension\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Symfony\Component\Console\Input\InputOption;
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
     * List of jobs to skip.
     *
     * @var  array  $skip
     */

    protected $skip = [
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonClass',
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonComposer',
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonLang',
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonServiceProvider'
    ];

    /**
     * Execute the console command.
     */
    public function fire(AddonManager $addons)
    {
      list($vendor, $type, $slug, $path) =
          _resolveAddonNamespace($this->argument('namespace'), $this->option('shared'));

      if ($type === 'module') {

        /* Get the default module template from the registry */
        if($this->download($template = _config('config.default-module'), $this->option('force'))){

            $this->logo();
            $context = $this->getContext($this->getTemplateMetadata($template),[
              'vendor' => $vendor], true);
            $this->dispatch(new ScaffoldModule($vendor, $type, $slug, $path,
                                $this->getBuilderPath('default-module/template/module'), $context));
            $this->info("Builder has successfully created a module addon from '$template'");

            return;
        } else {
          /* If anything goes wrong - which happens - fallback to Pyro make:addon */
          $this->ignoreJobs = false;
        }
      }
      parent::fire($addons);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
          ['force', null, InputOption::VALUE_NONE, "Indicates whether to force a fresh download of the 'default-module'."],
          ['shared', null, InputOption::VALUE_NONE, 'Indicates if the addon should be created in shared addons.'],
          ['migration', null, InputOption::VALUE_NONE, 'Indicates if a fields migration should be created.'],
        ];
    }
}
