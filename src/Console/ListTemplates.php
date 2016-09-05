<?php namespace Websemantics\BuilderExtension\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Github\Client;

/**
 * Class List.
 *
 * List available builder templates.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

class ListTemplates extends Spinner
{
    use DispatchesJobs;

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
    protected $description = 'List available Builder templates';

    /**
     * Execute the console command.
     */
    public function handle(Client $client)
    {

      $type = $this->argument('addon');
      $filter = in_array($type, config('streams::addons.types'));
      $bar = $this->createProgressIndicator();
      $logo = view('websemantics.extension.builder::logo')->render();

      $this->output->block($logo, null, 'fg=green;bg=black', ' ', true);


      $bar->start('Loading Builder templates ...');

      // $repos = $client->api('user')->repositories(config("websemantics.extension.builder::templates.username"));

      $repos = [
        [
          'name' => 'default-module',
          'description' => 'Default module template for Pyro Builder Extension'
        ],
        [
          'name' => 'default-extension',
          'description' => 'Default extension template for Pyro Builder Extension',
          'type' => 'Extension'
        ]
      ];

      $repos = collect($repos)->map(function ($values) {
        return array_only($values, ['name', 'description']);
      })->filter(function ($repo) use ($filter, $type){
          return $filter ? str_contains($repo['name'], "-$type") : true;
      });


      $bar->finish('Available ' . title_case($filter ? "$type " : ''). 'templates                ');
      $headers = ['Name', 'Description'];
      $this->table($headers, $repos);

    }
}
