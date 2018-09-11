<?php

namespace Websemantics\BuilderExtension\Console;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Websemantics\BuilderExtension\Traits\IgnoreJobs;
use Websemantics\BuilderExtension\Command\MakeStream;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class Make.
 *
 * Overrides core Stream Make command to avoid conflic with Builder extension.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 *
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc
 */
class Make extends \Anomaly\Streams\Platform\Stream\Console\Make
{
    /* Black magic */
    use DispatchesJobs, IgnoreJobs {
       IgnoreJobs::dispatch insteadof DispatchesJobs;
    }

    /**
     * Give the cold shoulder to these jobs,.
     *
     * @var array
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
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityTableBuilder',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityRouter',
      'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntityCriteria',
    ];

    /**
     * Execute the console command.
     */
    public function handle(AddonCollection $addons)
    {
        $schema = $this->argument('schema');
        $stream_slug = explode(':', trim($schema))[0];
        $addon = $addons->get($this->argument('addon'));
        $path = $addon->getPath();

        $this->call(
          'make:migration',
          [
              'name' => 'create_'.$addon->getSlug().'_'.$stream_slug.'_fields',
              '--addon' => $addon->getNamespace(),
              '--stream' => $schema,
              '--fields' => true,
          ]
      );

        /* Sleep for 4 seconds to make sure fields migration has a different/ealier
        time tagto the stream migration file */
        sleep(4);

        $this->call(
          'make:migration',
          [
              'name' => 'create_'.$stream_slug.'_stream',
              '--addon' => $addon->getNamespace(),
              '--stream' => $schema,
          ]
      );

      /* After a successful stream migration, create a seeder template file */
      $this->dispatch(new MakeStream($stream_slug, $path));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['schema', InputArgument::REQUIRED, 'The entity\'s stream slug or schema (Builder extension).'],
            ['addon', InputArgument::REQUIRED, 'The addon in which to put the new entity namespace.'],
        ];
    }
}
