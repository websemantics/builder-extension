<?php

namespace Websemantics\BuilderExtension\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Symfony\Component\Console\Input\InputOption;
use Websemantics\BuilderExtension\Traits\Registry;
use Websemantics\BuilderExtension\Traits\IgnoreJobs;
use Websemantics\BuilderExtension\Command\ScaffoldTemplate;

/**
  * Class MakeAddon.
  *
  * Override core MakeAddon command to generate module files that are
  * compatible with the Builder extension.
  *
  * @link      http://websemantics.ca/ibuild
  * @link      http://ibuild.io
  *
  * @author    WebSemantics, Inc. <info@websemantics.ca>
  * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
  * @copyright 2012-2016 Web Semantics, Inc
  */
 class MakeAddon extends \Anomaly\Streams\Platform\Addon\Console\MakeAddon
 {
     use Registry;

    /* Black magic */
    use DispatchesJobs, IgnoreJobs {
       IgnoreJobs::dispatch insteadof DispatchesJobs;
    }

    /**
     * Give the cold shoulder to these jobs,.
     *
     * @var array
     */
    protected $ignore = [
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonClass',
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonComposer',
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonLang',
      'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonServiceProvider',
    ];

    /**
     * Execute the console command.
     */
    public function fire(AddonManager $addons)
    {
      list($vendor, $type, $slug, $path) =
        _resolveAddonNamespace($this->argument('namespace'), $this->option('shared'));

      if ($type === 'module') {

        $this->logo();

        /* Get the default module template from the registry */
        if ($this->download($template = _config('config.default-module'), $this->option('force'))) {

            $context = $this->getTemplateContext($template,
            ['vendor' => $vendor, 'slug' => $slug], true);

            $this->dispatch(new ScaffoldTemplate($vendor, $type, $slug,
                                $this->getBuilderPath(_config('config.default-module')),
                                $path, $context));

            $this->info("Builder has successfully created a module addon from '$template'");

            return;
        } else {
            /* When things go wrong - which does happen sometimes - fallback to Pyro make:addon */
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
        ['force', null, InputOption::VALUE_NONE, "Indicates whether to force a fresh download of the '"._config('config.default-module')."'."],
        ['shared', null, InputOption::VALUE_NONE, 'Indicates if the addon should be created in shared addons.'],
        ['migration', null, InputOption::VALUE_NONE, 'Indicates if a fields migration should be created.'],
      ];
    }
 }
