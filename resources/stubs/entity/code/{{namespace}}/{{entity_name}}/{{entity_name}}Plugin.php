<?php namespace {{vendor_name}}\{{module_name}}Module\{% if config.namespace_folder == true %}{{namespace}}\\{% endif %}{{entity_name}};

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;

/**
 * Class {{entity_name}}Plugin
 *
{{config.docblock}}
 * @package   {{vendor_name}}\{{module_name}}Module\{% if config.namespace_folder == true %}{{namespace}}\\{% endif %}{{entity_name}}
 */

class {{entity_name}}Plugin extends Plugin
{

    /**
     * The {{entity_name|lower}} repository.
     *
     * @var {{entity_name}}Repository
     */
    protected ${{entity_name|lower|str_plural}};

    /**
     * Create a new {{entity_name}}Plugin instance.
     *
     * @param {{entity_name}}Repository ${{entity_name|lower|str_plural}}
     */
    public function __construct({{entity_name}}Repository ${{entity_name|lower|str_plural}})
    {
        $this->{{entity_name|lower|str_plural}} = ${{entity_name|lower|str_plural}};
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
