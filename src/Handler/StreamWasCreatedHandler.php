<?php namespace Websemantics\BuilderExtension\Handler;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Stream\Event\StreamWasCreated;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Websemantics\BuilderExtension\Command\GenerateEntity;

/**
 * Class StreamWasCreatedHandler
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class StreamWasCreatedHandler {

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
	 * Handle the event.
	 *
	 * @param  StreamWasCreated  $event
	 * @return void
	 */
	public function handle(StreamWasCreated $event)
	{
		$stream = $event->getStream();
		foreach ($this->modules as $module) {
			if(in_array($stream->getNamespace(), bxGetNamespaces($module))){
  		    $this->dispatch(new GenerateEntity($module, $stream));
			}
		}
	}

}
