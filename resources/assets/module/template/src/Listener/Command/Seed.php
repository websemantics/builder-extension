<?php 

namespace {vendor_name}\{module_name}Module\Listener\Command;

use App\Console\Kernel;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class Seed
 *
 * This command will seed the current module
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\Listener\Command
 */

class Seed implements SelfHandling
{
   /*
    * var $namespace
    */
    protected $namespace = null;

    /**
     * Set the commaond addon namespace.
     *
     * @param String $namespace, the module / addon namespace
     */
    function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Handle the command.
     *
     * @param Kernel $console
     */
    public function handle(Kernel $console)
    {
        $console->call('db:seed', [
            '--addon' => $this->namespace,
            '--force' => true,
        ]);
    }
}
