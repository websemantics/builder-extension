<?php namespace {{vendor_name}}\{{module_name}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;

/**
 * Interface {{entity_name}}Interface
 *
{{config.docblock}}
 * @package   {{vendor_name}}\{{module_name}}Module\{% if config.namespace_folder == true %}{{namespace}}\{% endif %}{{entity_name}}\Contract
 */

interface {{entity_name}}Interface extends EntryInterface
{

}
