<?php namespace {vendor_name}\{module_name}Module\{namespace_folder}{entity_name};

use {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}\Contract\{entity_name}Interface;
use Anomaly\Streams\Platform\Model\{namespace}\{namespace}{stream_slug}EntryModel;

/**
 * Class {entity_name}Model
 *
{docblock}
 * @package   {vendor_name}\{module_name}Module\{namespace_folder}{entity_name}
 */

class {entity_name}Model extends {namespace}{stream_slug}EntryModel implements {entity_name}Interface
{

    /**
     * The number of minutes to cache query results.
     *
     * @var null|false|int
     */
    protected $ttl = false;

}