<?php namespace Websemantics\BuilderExtension\Handler;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\Module\Event\ModuleWasInstalled;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Websemantics\BuilderExtension\Command\ModifyModule;
use Websemantics\BuilderExtension\Command\SeedModule;

/**
 * Class ModuleWasInstalledHandler
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class ModuleWasInstalledHandler {

  use DispatchesJobs;

	protected $modules;

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct(ModuleCollection $moduleCollection)
	{
		$this->modules = $moduleCollection->withConfig('builder');
	}

	/**
	 * Dispaches two jobs, 'ModifyModule' and 'SeedModule' *question of configuration
	 *
	 * @param  ModuleWasInstalled  $event
	 * @return void
	 */
	public function handle(ModuleWasInstalled $event)
	{
		$module = $event->getModule();

		if(count(_getNamespaces($module)) > 0){

        if(_landingPageOption($module)){
          $this->dispatch(new ModifyModule($module));
        }

        if(_seedingOption($module) === 'yes'){
    			$this->dispatch(new SeedModule($module));
    		}
		}
	}
}
