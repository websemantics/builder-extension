<?php namespace Websemantics\EntityBuilderExtension\Handlers\Events;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Anomaly\Streams\Platform\Addon\Module\Event\ModuleWasInstalled;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Websemantics\EntityBuilderExtension\Command\ModifyModule;

/**
 * Class ModuleWasInstalledHandler
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class ModuleWasInstalledHandler {

  use DispatchesCommands;

	protected $modules;
	
	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct(ModuleCollection $moduleCollection)
	{
		$this->modules = $moduleCollection->all();
	}

	/**
	 * Handle the event.
	 *
	 * @param  ModuleWasInstalled  $event
	 * @return void
	 */
	public function handle(ModuleWasInstalled $event)
	{
		$module = $event->getModule();

		$namespaces = array_get($module->hasConfig('builder'), 'namespaces', []);

		if(count($namespaces) > 0){
      $this->dispatch(new ModifyModule($module));
		}

	}

}
