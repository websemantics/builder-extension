<?php namespace {{vendor_name}}\{{module_name}}Module\{% if config.namespace_folder == true %}{{namespace}}\\{% endif %}{{entity_name}}\Table;

use {{vendor_name}}\{{module_name}}Module\{% if config.namespace_folder == true %}{{namespace}}\\{% endif %}{{entity_name}}\{{entity_name}}Model;

/**
 * Class {{entity_name}}TableColumns
 *
{{config.docblock}}
 * @package   {{vendor_name}}\{{module_name}}Module\{% if config.namespace_folder == true %}{{namespace}}\\{% endif %}{{entity_name}}\Table
 */

class {{entity_name}}TableColumns
{

    /**
     * Handle the columns.
     *
     * @param {{entity_name}}TableBuilder $builder
     */
    public function handle({{entity_name}}TableBuilder $builder)
    {
        $builder->setColumns([]);
    }
}
