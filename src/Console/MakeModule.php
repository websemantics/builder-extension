<?php namespace Websemantics\EntityBuilderExtension\Console;

use Websemantics\EntityBuilderExtension\Console\Command\ScaffoldModule;
use Anomaly\Streams\Platform\Addon\Command\RegisterAddons;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MakeModule
 *
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class MakeModule extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module for the Entity Builder.';

 	/**
     * Execute the console command.
     * 
     */
    public function fire()
    {
    	$type = 'module';
        $vendor = $this->argument('vendor');
        $slug = $this->argument('slug');

        if (empty($vendor) || empty($slug) ) {
            throw new \Exception("Provide the vendor and module's slug");
        }

        $this->dispatch(new ScaffoldModule($vendor, $type, $slug, $this));

        $this->dispatch(new RegisterAddons());

        if ($type == 'module') {
            $this->call(
                'make:migration',
                [
                    'name'     => 'create_' . $slug . '_fields',
                    '--addon'  => "{$vendor}.{$type}.{$slug}",
                    '--fields' => true
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
            ['vendor', InputArgument::REQUIRED, 'The module\'s vendor.'],
            ['slug', InputArgument::REQUIRED, 'The module\'s slug.']
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
            ['shared', null, InputOption::VALUE_NONE, 'Indicates if the module should be created in shared addons.']
        ];
    }
}
