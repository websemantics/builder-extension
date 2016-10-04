<?php namespace Websemantics\BuilderExtension\Command;

use Websemantics\BuilderExtension\Traits\TemplateProcessor;
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
  use TemplateProcessor;

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
        $this->setParser(app('Websemantics\BuilderExtension\Support\TwigParser'));
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
        $dest = $module->getPath();

        $data = [
            'config' => _config('builder', $module),
            'vendor' => $module->getVendor(),
            'module_name' => studly_case($module->getSlug())
        ];

        $src = __DIR__.'/../../resources/stubs/module';

        try {
          if(_config('builder.landing_page', $module)){

            /* adding routes to the module service provider class
            (currently, just for the optional landing (home) page) */
            $this->processFile("$dest/src/". $data['module_name'] .'ModuleServiceProvider.php',
                ['routes' => $src.'/routes.php'], $data);

            /* adding sections to the module class
            (currently, just for the optional landing (home) page)*/
            $this->processFile("$dest/src/". $data['module_name'] .'Module.php',
                              ['sections' => $src.'/sections.php'], $data, true);
          }

          /* generate sitemap for the module main stream */
          if($stream_slug = _config('builder.sitemap.stream_slug', $module)){
            $data['entity_name'] =  studly_case(str_singular($stream_slug));
            $data['repository_name'] =  str_plural($stream_slug);
            $this->files->parseDirectory("$src/config", "$dest/resources/config", $data);
          }

          /* adding module icon */
          $this->processVariable("$dest/src/". $data['module_name'] .'Module.php',
          ' "'._config('builder.icon', $module).'"','protected $icon =', ';');

        } catch (\PhpParser\Error $e) {
            die($e->getMessage());
        }
    }
}
