<?php namespace Websemantics\EntityBuilderExtension\Command\Handler;

use Websemantics\EntityBuilderExtension\Command\ModifyModule;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;
use Websemantics\EntityBuilderExtension\Filesystem\Filesystem;
use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Support\Parser;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Websemantics\EntityBuilderExtension\Command\GenerateEntity;


/**
 * Class ModifyModuleHandler
 *
 * Here we will only handle Fields creations
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @package   Websemantics\EntityBuilderExtension
 */

class ModifyModuleHandler
{

  use DispatchesCommands;

    /**
     * The file system utility.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The parser utility.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * The Stream Repository.
     *
     * @var StreamRepositoryInterface
     */
    protected $streamRepository;

    /**
     * Create a new ModifyModuleHandler instance.
     *
     * @param Filesystem  $files
     * @param Parser      $parser
     * @param Application $application
     */
    function __construct(Filesystem $files, Parser $parser, StreamRepositoryInterface $streamRepository)
    {
        $this->files            = $files;
        $this->parser           = $parser;
        $this->streamRepository = $streamRepository;
    }

    /**
     * Handle the command.
     *
     * @param ModifyModule $command
     */
    public function handle(ModifyModule $command)
    { 
        $module     = $command->getModule();
        $moduleName = studly_case($module->getSlug());

        $destination = $module->getPath();

        $namespaces = array_get($module->hasConfig('builder'), 'namespaces', []);

        /* Anything here!! */
    }

}



