<?php namespace Websemantics\BuilderExtension\Console;

/**
 * Class List.
 *
 * List available Builder templates.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

class ListTemplates extends Registry
{
    /**
     * The console command signature.
     *
     * @var string
     */

    protected $signature = 'builder:list {addon=all : module, extension, field_type, plugin, theme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List available Builder addon templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $this->list($this->argument('addon'));
    }
}
