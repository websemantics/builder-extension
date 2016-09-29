<?php namespace {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}};

use {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}}\Contract\{{entity_name}}RepositoryInterface;
{{extends_repository_use}}

/**
 * Class {{entity_name}}Repository
 *
{{docblock}}
 * @package   {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}}
 */

class {{entity_name}}Repository {{extends_repository}}implements {{entity_name}}RepositoryInterface
{
    /**
     * The {{entity_name|lower}} model.
     *
     * @var {{entity_name}}Model
     */
    protected $model;

    /**
     * Create a new {{entity_name}}Repository instance.
     *
     * @param {{entity_name}}Model $model
     */
    public function __construct({{entity_name}}Model $model)
    {
        $this->model = $model;
    }

    /**
     * Find a model by id or list of attributes
     *
     * @param int / array $attributes, value of record (id) or list of (attributes)
     */
    public function find($attributes)
    {
        if(is_array($attributes)){
            return $this->model->where($attributes)->first();
        }

        return parent::find(/* $id */ $attributes);
    }

    /**
     * Find a model by a specific field
     *
     * @param string $field, the field to find by
     * @param string / int, $value, value of that field
     * @param string $operation, the relational operation
     */
    public function findBy($field, $value, $operation = '=')
    {
        return $this->model->where($field, $operation, $value)->first();
    }

    /**
     * Find a model by a list of attributes, if not found, attept to create one
     *
     * @param array $attributes, list of attributes values
     */
    public function findOrNew(array $attributes)
    {
        if(is_null($model = $this->find($attributes))){
            return $this->create($attributes);
        }

        return $model;
    }
}
