<?php namespace Websemantics\BuilderExtension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Websemantics\BuilderExtension\Traits\Registry;

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
   * The console command signature.
   *
   * @var string
   */

  protected $signature = 'builder:make {template : template name} {--force}';

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
  public function handle()
  {
    $this->logo();

    if($this->download($template = $this->argument('template'), $this->option('force'))){

      /*
        read the Builder template metadata and return the templaet context object
      */
      $context = $this->getContext($this->getTemplateMetadata($template));

    } else {
      $this->output->error('Builder template not found');
    }
  }
}
