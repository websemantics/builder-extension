<?php namespace {vendor_name}\{module_name}Module\Listener;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\Module\Event\ModuleWasInstalled;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class BootstrapHandler
 * 
 * Use this class to dispatch any job / command when this module has been installed.
 *
{docblock}
 * @package   {vendor_name}\{module_name}Modul\Listener
 */

class BootstrapHandler implements SelfHandling
{
    use DispatchesJobs;

    /* 
     * Addon / Module namespace
     *
     */
    protected $namespace = '{vendor_name_lower}.module.{module_name_lower}';

    /* 
     * List of jobs to be dispatched
     *
     */
    protected $jobs = [];

     /**
     * Handle the e.
     *
     * @param  ModuleWasInstalled  $e
     * @return void
     */
    public function handle(ModuleWasInstalled $e)
    {
        $module = $e->getModule();

        /* Only bootstrap when a match */
        if($module->getId() === $this->namespace){
            
            /* Dispatch all avilable jobs */
            foreach ($this->jobs as $job) 
            {
                $this->dispatch(app($job)->setNamespace($this->namespace));
            }
        }
    }
}
