<?php namespace {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}\Contract;

/**
 * Interface {entity_name}RepositoryInterface
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}\Contract
 */

interface {entity_name}RepositoryInterface
{

    /**
     * Create a new {entity_name_lower}.
     *
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes);

   
}
