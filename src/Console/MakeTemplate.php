<?php namespace Websemantics\BuilderExtension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Websemantics\BuilderExtension\Traits\Registry;
use Websemantics\BuilderExtension\Command\ScaffoldTemplate;

/**
 * Class List.
 *
 * Create a Builder template.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

class MakeTemplate extends Command
{
  use DispatchesJobs;
  use Registry;

  /**
   * The console command name.
   *
   * @var string
   */

  protected $name = 'builder:make';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create an addon from a Builder template';

  /**
   * Download and make an addon from a Builder template.
   * --force will re-download an exist template
   */
   public function fire(AddonManager $addons)
  {
    $this->logo();

    if($this->download($template = $this->argument('template'), $this->option('force'))){

      /* read the Builder template metadata and return the templaet context object */
      $context = $this->getTemplateContext($template);
      $addon = _render('{{ vendor }}.{{ type }}.{{ slug }}', $context);

      list($vendor, $type, $slug, $path) = _resolveAddonNamespace($addon, $this->option('shared'));

      $this->dispatch(new ScaffoldTemplate($vendor, $type, $slug,
                         $this->getBuilderPath($template),
                         $path, $context));

      $this->info("Builder has successfully created '$addon' addon from '$template' at '$path'");

    } else {
      $this->output->error("Builder template not found. Use 'builder:list' command for a list of available templates.");
    }
  }

  /**
   * Get the command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
      return [
          ['template', InputArgument::REQUIRED, 'The template name to scaffold.'],
      ];
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions()
  {
    return [
      ['force', null, InputOption::VALUE_NONE, "Indicates whether to force a fresh download of the addon Builder's template."],
      ['shared', null, InputOption::VALUE_NONE, 'Indicates if the addon should be created in shared addons.'],
      ['migration', null, InputOption::VALUE_NONE, 'Indicates if a fields migration should be created.'],
    ];
  }
}
