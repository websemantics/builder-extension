<?php namespace {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}};

use Anomaly\Streams\Platform\Database\Seeder\Seeder;

/**
 * Class {{entity_name}}Seeder
 *
{{config.docblock}}
 * @package   {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}
 */

class {{entity_name}}Seeder extends Seeder
{

    /**
     * The ${{entity_name|lower|str_plural}} seeder data
     *
     * @var array
     */
    protected $data = [
        {{seeder_data|raw}}
    ];

    /**
     * The ${{entity_name|lower|str_plural}} repository.
     *
     * @var {{entity_name}}Repository
     */
    protected ${{entity_name|lower|str_plural}};

    /**
     * Create a new {{entity_name}}Seeder instance.
     *
     * @param {{entity_name}}Repository ${{entity_name|lower|str_plural}}
     */
    public function __construct({{entity_name}}Repository ${{entity_name|lower|str_plural}})
    {
        $this->{{entity_name|lower|str_plural}} = ${{entity_name|lower|str_plural}};
    }

    /**
     * Seed the ${{entity_name|lower|str_plural}}.
     */
    public function run()
    {
        $this->{{entity_name|lower|str_plural}}->truncate();

       foreach ($this->data as $item){
            $this->{{entity_name|lower|str_plural}}->create($item);
       }
    }
}
