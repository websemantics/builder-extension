<?php namespace Websemantics\BuilderExtension\Anomaly\Addon\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Websemantics\BuilderExtension\Anomaly\Addon\Console\Command\ScaffoldModule;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Anomaly\Streams\Platform\Addon\Console\Command\MakeAddonPaths;
use Websemantics\BuilderExtension\Traits\JobsDispatcher;
use Github\Client;

/**
 * Class MakeAddon.
 *
 * Override core MakeAddon command to generate module files that are compatible with Builder extension.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
 class MakeAddon extends \Anomaly\Streams\Platform\Addon\Console\MakeAddon
{
    use DispatchesJobs, JobsDispatcher {
       JobsDispatcher::dispatch insteadof DispatchesJobs;
    }

    protected $name = 'make:addon';

    /**
     * Execute the console command.
     */
    public function fire(AddonManager $addons)
    {

      $this->info('Available templates');
      $this->error('Stuff this on the screen');
      $this->line('Display this on the screen');

      $headers = ['Name', 'Email'];
      $users = \Anomaly\UsersModule\User\UserModel::all(['display_name', 'email'])->toArray();
      $this->table($headers, $users);

      dd('hhh');
      die();

      $client = new Client();
      $repos = $client->api('user')->repositories('pyrocms-templates');

      foreach ($repos as $repo) {
        dump($repo['description']);
      }

      die();

        list($vendor, $type, $slug, $path) =
            ebxResolveAddonNamespace($this->argument('namespace'), $this->option('shared'));

        if ($type === 'module') {
            $this->dispatch(new ScaffoldModule($vendor, $type, $slug, $path));
        }

        parent::fire($addons);
    }
}
