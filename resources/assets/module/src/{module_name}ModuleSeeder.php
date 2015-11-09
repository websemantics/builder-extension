<?php namespace {vendor_name}\{module_name}Module;

use Anomaly\Streams\Platform\Database\Seeder\Seeder;

/**
 * Class {module_name}ModuleSeeder
 *
{docblock}
 * @package   {vendor_name}\{module_name}Modul
 */

class {module_name}ModuleSeeder extends Seeder
{
	protected $seeders = [];
    /**
     * Seed the localization module.
     */
    public function run()
    {   
    		foreach ($this->seeders as $seeder) {
        	    $this->call($seeder);
    		}             
    }
}