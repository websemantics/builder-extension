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
     * List the Builder's registery templates.
     */
    public function handle()
    {
      $this->logo();

      $type = $this->argument('addon');
      $filter = in_array($type, config('streams::addons.types'));
      $title = title_case($filter ? "$type " : ''). 'templates                ';

      /*
        Get a list of all repositories from cache or builder templates registry.
        Return all addon types or filter on $type provided
      */

      $repos = app('cache')->remember($this->getCacheKey($this->registry), $this->ttl,
        function() use($type, $filter) {
          return collect($this->client->api('user')->repositories($this->registry))
            ->map(function ($values) {
              return array_only($values, ['name', 'description']);
            })->filter(function ($repo) use ($filter, $type){
              return $filter ? str_contains($repo['name'], "-$type") : true;
          });
        }
      );

      if($repos->count() > 0) {
        $this->block("Available $title");
        $this->table(['Name', 'Description'], $repos);
      } else {
        $this->block("There are no available $title");
      }
    }
}
