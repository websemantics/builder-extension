<?php namespace Websemantics\BuilderExtension\Console;

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

class MakeTemplates extends Registry
{
  /**
   * The console command signature.
   *
   * @var string
   */

  protected $signature = 'builder:make {template : template name}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create an addon from a Builder template';

  /**
   * Execute the console command.
   */
  public function handle(Client $client)
  {

  }
}
