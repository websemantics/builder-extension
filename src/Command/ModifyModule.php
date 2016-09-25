<?php namespace Websemantics\BuilderExtension\Command;

use Websemantics\BuilderExtension\Traits\FileProcessor;
use Websemantics\BuilderExtension\Parser\ModuleNameParser;
use Websemantics\BuilderExtension\Parser\VendorNameParser;
use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class ModifyModule.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class ModifyModule
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
        $this->setFiles(app('Websemantics\BuilderExtension\Filesystem\Filesystem'));
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
        $dest = $module->getPath();
        $source = __DIR__.'/../../resources/stubs/module';

        try {
          if(_landingPageOption($module)){

            /* adding routes to the module service provider class
            (currently, just for the optional landing (home) page) */
            $this->processFile(
                $dest.'/src/'.$data['module_name'].'ModuleServiceProvider.php',
                ['routes' => $source.'/routes.php'],
                $data
            );

            /* adding sections to the module class
            (currently, just for the optional landing (home) page)*/
            $this->processFile(
                $dest.'/src/'.$data['module_name'].'Module.php',
                ['sections' => $source.'/sections.php'],
                $data,
                true
            );
          }
        } catch (\PhpParser\Error $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get template data.
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
