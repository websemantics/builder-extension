<?php namespace {vendor_name}\{module_name}Module\{namespace_folder}{entity_name};

use {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}\Contract\{entity_name}Interface;
use {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}\Contract\{entity_name}RepositoryInterface;

/**
 * Class {entity_name}Repository
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}
 */

class {entity_name}Repository implements {entity_name}RepositoryInterface
{

    /**
     * The {entity_name_lower} model.
     *
     * @var {entity_name}Model
     */
    protected $model;

    /**
     * Create a new {entity_name}Repository instance.
     *
     * @param {entity_name}Model $model
     */
    public function __construct({entity_name}Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new {entity_name_lower}.
     *
     * @param array $attributes
     * @return static
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

}
