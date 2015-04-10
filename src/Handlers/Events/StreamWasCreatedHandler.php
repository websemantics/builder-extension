<?php namespace Websemantics\EntityBuilderExtension\Handlers\Events;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Anomaly\Streams\Platform\Stream\Event\StreamWasCreated;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Websemantics\EntityBuilderExtension\Command\GenerateEntity;

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
			
			$namespaces = array_get($module->hasConfig('builder'), 'namespaces', []);

			if(in_array($stream->getNamespace(), $namespaces))
        $this->dispatch(new GenerateEntity($module, $stream));

		}
	}

}
