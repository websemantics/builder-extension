<?php namespace Websemantics\BuilderExtension\Handler;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Assignment\Event\AssignmentWasCreated;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Websemantics\BuilderExtension\Command\ModifyEntity;

/**
 * Class AssignmentWasCreatedHandler
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class AssignmentWasCreatedHandler {

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
	 * @param  AssignmentWasCreated  $event
	 * @return void
	 */
	public function handle(AssignmentWasCreated $event)
	{
		$assignment = $event->getAssignment();
		$stream = $assignment->getStream();

		foreach ($this->modules as $module) {
			if(in_array($stream->getNamespace(), ebxGetNamespaces($module))){
     		 $this->dispatch(new ModifyEntity($module, $stream, $assignment));
			}

		}
	}

}
