<?php namespace {vendor_name}\{module_name}Module\{namespace_folder}{entity_name};

use {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}\Contract\{entity_name}RepositoryInterface;
use Anomaly\Streams\Platform\Database\Seeder\Seeder;

/**
 * Class {entity_name}Seeder
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}
 */
class {entity_name}Seeder extends Seeder
{

    /**
     * The ${entity_name_lower_plural} repository.
     *
     * @var {entity_name}RepositoryInterface
     */
    protected ${entity_name_lower_plural};

    /**
     * Create a new {entity_name}Seeder instance.
     *
     * @param {entity_name}RepositoryInterface ${entity_name_lower_plural}
     */
    public function __construct({entity_name}RepositoryInterface ${entity_name_lower_plural})
    {
        $this->{entity_name_lower_plural} = ${entity_name_lower_plural};
    }

    /**
     * Seed the ${entity_name_lower_plural}.
     */
    public function run()
    {
        $this->{entity_name_lower_plural}->create(
            [
                /* 'name'            => 'Example', */
            ]
        );
    }
}
