<?php namespace Websemantics\EntityBuilderExtension\Handlers\Events;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Anomaly\Streams\Platform\Stream\Event\StreamWasCreated;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Websemantics\EntityBuilderExtension\Command\GenerateEntity;

/**
 * Class StreamWasCreatedHandler
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class StreamWasCreatedHandler {

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
	 * @param  StreamWasCreated  $event
	 * @return void
	 */
	public function handle(StreamWasCreated $event)
	{
		$stream = $event->getStream();

		foreach ($this->modules as $module) {
			
			$namespaces = ebxGetNamespaces($module);

			if(in_array($stream->getNamespace(), $namespaces)){
        $this->dispatch(new GenerateEntity($module, $stream));
			}

		}
	}

}
