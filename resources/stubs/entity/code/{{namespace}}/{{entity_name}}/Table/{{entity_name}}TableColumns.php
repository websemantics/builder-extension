<?php namespace {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}\Table;

use {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}\{{entity_name}}Model;

/**
 * Class {{entity_name}}TableColumns
 *
{{config.docblock}}
 * @package   {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}\Table
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
