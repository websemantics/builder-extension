<?php namespace Websemantics\BuilderExtension\Command;

use Anomaly\Streams\Platform\Application\Application;
use Websemantics\BuilderExtension\Filesystem\Filesystem;

/**
 * Class ScaffoldTemplate.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension\Anomaly\Addon\Console\Command
 */

class ScaffoldTemplate
{
    /**
     * The addon path.
     *
     * @var string
     */
    private $dist;

    /**
     * The addon slug.
     *
     * @var string
     */
    private $slug;

    /**
     * The addon type.
     *
     * @var string
     */
    private $type;

    /**
     * The vendor slug.
     *
     * @var string
     */
    private $vendor;

    /**
     * Create a new ScaffoldTemplate instance.
     *
     * @param string, $vendor
     * @param string, $type
     * @param string, $slug
     * @param string, $dist
     * @param string, $src, Builder default-module template path
     * @param array, $context, Builder template context/data object/array
     */
    public function __construct($vendor, $type, $slug, $src, $dist, $context)
    {
        $this->vendor = $vendor;
        $this->type = $type;
        $this->slug = $slug;
        $this->src = $src;
        $this->dist = $dist;
        $this->context = $context;
    }

    /**
     * Handle the command.
     *
     * @return string
     */
    public function handle(Filesystem $files)
    {
        /* Create addon folder */
        $files->makeDirectory($this->dist, 0755, true, true);

        /* Copy/parse Builder's template files */
        $files->parseDirectory($this->src.'/template', $this->dist.'/', $this->context);

        return $this->dist;
    }
}
