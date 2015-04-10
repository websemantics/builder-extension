<?php namespace {vendor_name}\{module_name}Module\{namespace_folder}{entity_name};

use {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}\Contract\{entity_name}Interface;
use Anomaly\Streams\Platform\Model\{namespace}\{namespace}{entity_name_plural}EntryModel;

/**
 * Class {entity_name}Model
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}
 */

class {entity_name}Model extends {namespace}{entity_name_plural}EntryModel implements {entity_name}Interface
{

    /**
     * The cache minutes.
     *
     * @var int
     */
    protected $cacheMinutes = 9999;

}