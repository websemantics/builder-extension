<?php namespace {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.group == true %}{{namespace|studly_case}}\{% endif %}{{entity_name}}\Contract;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;

/**
 * Interface {{entity_name}}Interface
 *
{{config.docblock}}
 * @package   {{vendor|studly_case}}\{{module_slug|studly_case}}Module\{% if config.group == true %}{{namespace|studly_case}}\{% endif %}{{entity_name}}\Contract
 */

interface {{entity_name}}Interface extends EntryInterface
{

}
