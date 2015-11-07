<?php namespace Websemantics\EntityBuilderExtension\Command\Handler;

use Websemantics\EntityBuilderExtension\Command\Traits\FileProcessor;
use Websemantics\EntityBuilderExtension\Command\ModifyModule;
use Websemantics\EntityBuilderExtension\Parser\ModuleNameParser;
use Websemantics\EntityBuilderExtension\Parser\VendorNameParser;
use Websemantics\EntityBuilderExtension\Filesystem\Filesystem;
use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Support\Parser;


/**
 * Class ModifyModuleHandler
 *
 * This handles 'ModuleWasInstalled' event
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @package   Websemantics\EntityBuilderExtension
 */

class ModifyModuleHandler
{
    use  FileProcessor;

    /**
     * Create a new ModifyModuleHandler instance.
     *
     * @param Filesystem  $files
     * @param Parser      $parser
     * @param Application $application
     */
    function __construct(Filesystem $files, Parser $parser)
    {
        $this->setFiles($files);
        $this->setParser($parser);
    }

    /**
     * Handle the command.
     *
     * Add a default Module route, language entries etc per Module
     *
     * @param ModifyModule $command
     */
    public function handle(ModifyModule $command)
    { 

        $module = $command->getModule();

        $data = $this->getTemplateData($module);

        $destination = $module->getPath();

        $folder   = __DIR__ . '/../../../resources/assets/module';

        try {
                                
            $this->processFile(
                $destination . '/src/' . $data['module_name'] . 'ModuleServiceProvider.php',
                ['routes' => $folder.'/routes.php'], $data);

            $this->processFile(
                $destination . '/src/' . $data['module_name'] . 'Module.php',
                ['sections' => $folder.'/sections.php'], $data, true);

            $this->processFile(
                $destination . '/resources/lang/en/addon.php',
                ['section' => $folder.'/addon.php'], $data);

        } catch (\PhpParser\Error $e) {
            die($e->getMessage());
        }

    }

    /**
     * Get the template data from a stream object.
     *
     * @param  Module $module
     * @param  StreamInterface $stream
     * @return array
     */
    
    protected function getTemplateData(Module $module)
    {
        $moduleName = (new ModuleNameParser())->parse($module);

        return [
            'vendor_name'                   => (new VendorNameParser())->parse($module),
            'module_name'                   => $moduleName,
            'module_name_lower'             => strtolower($moduleName)
        ];
    }
}