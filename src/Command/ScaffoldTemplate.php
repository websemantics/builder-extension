<?php namespace Websemantics\BuilderExtension\Command;

use Websemantics\BuilderExtension\Traits\FileProcessor;
use Anomaly\Streams\Platform\Application\Application;
use Websemantics\BuilderExtension\Filesystem\Filesystem;
use Packaged\Figlet\Figlet;

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
    use FileProcessor;

    /**
     * The addon path.
     *
     * @var string
     */
    private $path;

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
     * @param string, $path
     * @param string, $src, Builder default-module template path
     * @param array, $context, Builder template context/data object/array
     */
    public function __construct($vendor, $type, $slug, $path, $src, $context)
    {
        $this->vendor = $vendor;
        $this->type = $type;
        $this->slug = $slug;
        $this->path = $path;
        $this->src = $src;
        $this->context = $context;
    }

    /**
     * Handle the command.
     *
     * @return string
     */
    public function handle(Filesystem $files)
    {
        $context = array_merge($this->getTemplateData(), $this->context);

        /* Make module's folder */
        $files->makeDirectory($this->path, 0755, true, true);

        /* Copy module template files */
        $files->parseDirectory($this->src.'/template', $this->path.'/', $context);

        return $this->path;
    }

    /**
     * Get the template data from a stream object.
     *
     * @param Module          $module
     * @param StreamInterface $stream
     *
     * @return array
     */
    protected function getTemplateData()
    {
        $moduleName = studly_case($this->slug);
        $vendorName = studly_case($this->vendor);

        return [
          'description' => 'Describe your module here',
          'docblock' => ' *',
          'vendor_name' => $vendorName,
          'vendor_name_lower' => strtolower($vendorName),
          'namespace' => strtolower($moduleName),
          'module_name' => $moduleName,
          'date' => date("Y-n-j"),
          'figlet_module_name' => Figlet::create(strtoupper($moduleName . ' Module'), 'small' /* slant */),
          'module_name_lower' => strtolower($moduleName),
        ];
    }
}
