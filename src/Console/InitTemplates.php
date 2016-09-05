<?php namespace Websemantics\BuilderExtension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

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

class InitTemplates extends Command
{
  use DispatchesJobs;

  /**
   * The console command signature.
   *
   * @var string
   */

  protected $signature = 'builder:init {template : template name}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a Builder template';

  /**
   * Execute the console command.
   */
  public function handle(Client $client)
  {
    /* Work in progress */
  }
}
