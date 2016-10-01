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
  * Override core MakeAddon command to generate supported addon files
  * (curently, module, theme) that are compatible with the Builder extension.
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

    /* Kung fu */
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
      $this->logo();

      list($vendor, $type, $slug, $path) =
        _resolveAddonNamespace($this->argument('namespace'), $this->option('shared'));

      /* construct an index for a potential template  */
      $index = "templates.$type" . (($type !== 'theme') ? "" : ($this->option('admin') ? '.0' : '.1'));

      /* the template option indicates that the user wants to create a template not the actual addon */
      $template = _config("config." . ($this->option('template') ? "default-template" : $index));

      if ($template && $this->download($template, $this->option('force'))) {

          $context = $this->getTemplateContext($template,
          ['vendor' => $vendor, 'slug' => $slug, 'type' => $type], true, $this->option('default'));

          $this->dispatch(new ScaffoldTemplate($vendor, $type, $slug,
                              $this->getBuilderPath($template),
                              $path, $context));

          $this->info("Builder has successfully created a $type addon from '$template'");

          return;
      } else { /* When things go wrong - which happens sometimes - fallback to Pyro make:addon */
        $this->ignoreJobs = false;
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
        ['default', null, InputOption::VALUE_NONE, "Indicates whether to use template default values."],
        ['template', null, InputOption::VALUE_NONE, "Indicates whether an addon or a addon template should be created."],
        ['admin', null, InputOption::VALUE_NONE, "Indicates whether the addon is an Admin theme."],
        ['force', null, InputOption::VALUE_NONE, "Indicates whether to force a fresh download of the template."],
        ['shared', null, InputOption::VALUE_NONE, 'Indicates if the addon should be created in shared addons.'],
        ['migration', null, InputOption::VALUE_NONE, 'Indicates if a fields migration should be created.'],
      ];
    }
 }
