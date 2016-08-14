<?php

namespace Websemantics\EntityBuilderExtension\Console;

use Websemantics\EntityBuilderExtension\Console\Command\ScaffoldModule;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Anomaly\Streams\Platform\Addon\Console\Command\MakeAddonPaths;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ModuleCreate.
 *
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 *
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 */
class ModuleCreate extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a module (for the Entity Builder).';

    /**
     * Execute the console command.
     */
    public function fire(AddonManager $addons)
    {

      $namespace = $this->argument('namespace');

      if (!str_is('*.*.*', $namespace)) {
          throw new \Exception("The namespace should be snake case and formatted like: {vendor}.module.{slug}");
      }

      list($vendor, $type, $slug) = array_map(
          function ($value) {
              return str_slug(strtolower($value), '_');
          },
          explode('.', $namespace)
      );

      $type = str_singular($type);

      if ($type !== 'module') {
          throw new \Exception("The namespace should be snake case and formatted like: {vendor}.module.{slug}");
      }

      $path = $this->dispatch(new MakeAddonPaths($vendor, $type, $slug, $this));

      $this->dispatch(new ScaffoldModule($path, $vendor, $type, $slug, $this));

      $addons->register();

      if ($type == 'module') {
          $this->call(
              'make:migration',
              [
                  'name' => 'create_'.$slug.'_fields',
                  '--addon' => "{$vendor}.{$type}.{$slug}",
                  '--fields' => true,
              ]
          );
      }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['namespace', InputArgument::REQUIRED, 'The addon\'s desired dot namespace.']
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['shared', null, InputOption::VALUE_NONE, 'Indicates if the addon should be created in shared addons.']
        ];
    }
}
