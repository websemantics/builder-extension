<?php namespace {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace|studly_case}}\{% endif %}{{entity_name}}\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryRepositoryInterface;

/**
 * Interface {{entity_name}}RepositoryInterface
 *
{{config.docblock}}
 * @package   {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace|studly_case}}\{% endif %}{{entity_name}}\Contract
 */

interface {{entity_name}}RepositoryInterface extends EntryRepositoryInterface
{

    /**
     * Truncate {{entity_name|lower}}.
     *
     * @return static
     */
    public function truncate();

    /**
     * Create a new {{entity_name|lower}}.
     *
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes);

}
