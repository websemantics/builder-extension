<?php namespace {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}};

use {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}}\Contract\{{entity_name}}Interface;
use Anomaly\Streams\Platform\Model\{{namespace}}\{{namespace}}{{stream_slug|studly_case}}EntryModel;

/**
 * Class {{entity_name}}Model
 *
{{docblock}}
 * @package   {{vendor_name}}\{{module_name}}Module\{{namespace_folder}}{{entity_name}}
 */

class {{entity_name}}Model extends {{namespace}}{{stream_slug|studly_case}}EntryModel implements {{entity_name}}Interface
{
    /**
     * Disable timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The number of minutes to cache query results.
     *
     * @var null|false|int
     */
    protected $ttl = false;

    /**
     * The attributes that are
     * not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Runtime cache.
     *
     * @var array
     */
    protected $cache = [];

}
