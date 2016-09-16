<?php namespace {vendor_name}\{module_name}Module\{namespace_folder}{entity_name};

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;

/**
 * Class {entity_name}Plugin
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}
 */

class {entity_name}Plugin extends Plugin
{

    /**
     * The {entity_name_lower} repository.
     *
     * @var {entity_name}Repository
     */
    protected ${entity_name_lower_plural};

    /**
     * Create a new {entity_name}Plugin instance.
     *
     * @param {entity_name}Repository ${entity_name_lower_plural}
     */
    public function __construct({entity_name}Repository ${entity_name_lower_plural})
    {
        $this->{entity_name_lower_plural} = ${entity_name_lower_plural};
    }

    /**
     * Get the plugin functions.
     *
     * @return array
     */
    public function getFunctions()
    {

    }
}
