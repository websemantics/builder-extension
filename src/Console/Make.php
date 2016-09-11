<?php namespace Websemantics\BuilderExtension\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Websemantics\BuilderExtension\Traits\IgnoreJobs;
use Websemantics\BuilderExtension\Command\MakeStream;

/**
 * Class Make.
 *
 * Overrides core Stream Make command to avoid conflic with Builder extension.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */
class Make extends \Anomaly\Streams\Platform\Stream\Console\Make
{
    /* Black magic */
    use DispatchesJobs, IgnoreJobs {
       IgnoreJobs::dispatch insteadof DispatchesJobs;
    }

    /**
     * Give the cold shoulder to these jobs,
     *
     * @var  array  $ignore
     */

    protected $ignore = [
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityCollection',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityController',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityFormBuilder',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityModel',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityModelInterface',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityObserver',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityPresenter',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityRepository',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityRepositoryInterface',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityRoutes',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityTableBuilder'
    ];

    /**
     * Execute the console command.
     */
    public function fire(AddonCollection $addons)
    {
      parent::fire($addons);

      $slug  = $this->argument('slug');
      $addon = $addons->get($this->argument('addon'));
      $path = $addon->getPath();

      /* After a successful stream migration, create a seeder template file */
      $this->dispatch(new MakeStream($slug, $path));
    }
}
