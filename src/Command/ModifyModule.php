<?php namespace Websemantics\EntityBuilderExtension\Command;

use Illuminate\Contracts\Bus\SelfHandling;
use Websemantics\EntityBuilderExtension\Traits\FileProcessor;
use Websemantics\EntityBuilderExtension\Parser\ModuleNameParser;
use Websemantics\EntityBuilderExtension\Parser\VendorNameParser;
use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class ModifyModule
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class ModifyModule implements SelfHandling
{
  use FileProcessor;

    /**
     * The module class.
     *
     * @var \Anomaly\Streams\Platform\Addon\Module\Module
     */
    protected $module;

    /**
     * Create a new ModifyModule instance.
     *
     * @param Module $module
     * @param Filesystem  $files
     * @param Parser      $parser
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
        $this->setFiles(app('Websemantics\EntityBuilderExtension\Filesystem\Filesystem'));
        $this->setParser(app('Anomaly\Streams\Platform\Support\Parser'));
    }

    /**
     * Handle the command.
     *
     * Add a default Module route, language entries etc per Module
     *
     */
    public function handle()
    {
        $module = $this->module;

        $data = $this->getTemplateData($module);

        $seeding = ebxSeedingOption($module);

        $destination = $module->getPath();

        $folder = __DIR__.'/../../resources/assets/module';

        try {

            /* Copy resources */
            $this->files->parseDirectory(
                $folder.'/resources',
                $destination.'/resources',
                $data
            );

            $this->processFile(
                $destination.'/src/'.$data['module_name'].'ModuleServiceProvider.php',
                ['routes' => $folder.'/routes.php'],
                $data
            );

            $this->processFile(
                $destination.'/src/'.$data['module_name'].'Module.php',
                ['sections' => $folder.'/sections.php'],
                $data,
                true
            );

            if($seeding === 'self'){
                /* Allow self seeder after a module install, */
                $this->processFile(
                    $destination.'/src/Listener/BootstrapHandler.php',
                    ['jobs' => $folder.'/jobs.php'],
                    $data,
                    true
                );
            }

        } catch (\PhpParser\Error $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get the template data from a stream object.
     *
     * @param Module          $module
     * @param StreamInterface $stream
     *
     * @return array
     */
    protected function getTemplateData(Module $module)
    {
        $moduleName = (new ModuleNameParser())->parse($module);

        return [
            'vendor_name' => (new VendorNameParser())->parse($module),
            'namespace' => $moduleName,
            'module_name' => $moduleName,
            'module_name_lower' => strtolower($moduleName),
        ];
    }

}
