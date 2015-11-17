<?php namespace {vendor_name}\{module_name}Module;

use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class {module_name}Module
 *
{docblock}
 * @package   {vendor_name}\{module_name}Modul
 */

class {module_name}Module extends Module
{
    /**
     * The navigation icon.
     *
     * @var string
     */
    protected $icon = 'addon';

    /**
     * The module sections.
     *
     * @var array
     */
    protected $sections = [
        'example'
    ];
}