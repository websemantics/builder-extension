<?php namespace Websemantics\BuilderExtension\Console;

use Github\Client;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Websemantics\BuilderExtension\Traits\Spinner;

/**
 */

class Registry extends Command
{
    use DispatchesJobs;

    /*
    * This might be useful in the future for multi-threading
    */
    use Spinner;

    /**
     * The Github api client.
     *
     * @var Github\Client
     */
    protected $client;

    /**
     * The Github registery organization
     *
     * @var string
     */
    protected $registry;

    /**
     * The number of minutes to cache api calls
     *
     * @var null|false|int
     */
    protected $ttl = false;

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
      parent::__construct();

      $this->client = $client;
      $this->registry = bxConfig('config.registry');
      $this->ttl = bxConfig('config.ttl');
    }

    /**
     * Print a block.
     *
     * @param string $message, user message
     * @param string $color, text color
     * @param string $background, background color
     * @return void
     */
    protected function block($message, $color = 'yellow', $background = 'black')
    {
      $this->output->block($message, null, "fg=$color;bg=$background");
    }

    /**
     * Get cache key
     *
     * @return string
     */
    protected function getCacheKey($key)
    {
        return md5("builder_extension_$key");
    }

    /**
     * Flush cache
     *
     * @return void
     */
    public function flush()
    {
      app('cache')->forget($this->registry);
    }

    /**
     * List the Builder's registery templates.
     *
     * @param string $type, addon type
     */
    public function list($type)
    {
      $filter = in_array($type, config('streams::addons.types'));
      $title = title_case($filter ? "$type " : ''). 'templates                ';
      $client = $this->client;

      $this->block(bxView('ascii.logo')->render(), 'green');

      /*
        Get a list of all repositories from cache or builder templates registry.
        Return all addon types or filter on $type provided  */

      $repos = app('cache')->remember($this->getCacheKey($this->registry), $this->ttl,
        function() use($type, $filter, $client) {
          return collect($client->api('user')->repositories($this->registry))
            ->map(function ($values) {
            return array_only($values, ['name', 'description']);
            })->filter(function ($repo) use ($filter, $type){
              return $filter ? str_contains($repo['name'], "-$type") : true;
          });
        }
      );

      if($repos->count() > 0) {
        $this->block("Available $title");
        $headers = ['Name', 'Description'];
        $this->table($headers, $repos);
      } else {
        $this->block("There are no available $title");
      }
    }
}
