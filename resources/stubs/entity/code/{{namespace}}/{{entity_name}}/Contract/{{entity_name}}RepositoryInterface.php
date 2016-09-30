<?php namespace {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}}\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryRepositoryInterface;

/**
 * Interface {{entity_name}}RepositoryInterface
 *
{{config.docblock}}
 * @package   {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}}\Contract
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
