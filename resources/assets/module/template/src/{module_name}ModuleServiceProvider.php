<?php namespace {vendor_name}\{module_name}Module;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class {module_name}ModuleServiceProvider
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module
 */

class {module_name}ModuleServiceProvider extends AddonServiceProvider
{
    protected $plugins = [];

    protected $routes = [];

    protected $bindings = [];

    protected $middleware = [];

    protected $listeners = [
    'Anomaly\Streams\Platform\Addon\Module\Event\ModuleWasInstalled' => 
    ['{vendor_name}\{module_name}Module\Listener\BootstrapHandler']];

    protected $providers = [];

    protected $singletons = [];

    protected $overrides = [];

    protected $mobile = [];
    
    protected $commands = [];

    public function register()
    {
    }

    public function map()
    {
    }
}


