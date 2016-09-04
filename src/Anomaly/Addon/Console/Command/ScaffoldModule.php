<?php namespace Websemantics\EntityBuilderExtension\Anomaly\Addon\Console\Command;

use Websemantics\EntityBuilderExtension\Traits\FileProcessor;
use Anomaly\Streams\Platform\Application\Application;
use Illuminate\Filesystem\Filesystem;
use Packaged\Figlet\Figlet;

/**
 * Class ScaffoldModule.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension\Anomaly\Addon\Console\Command
 */

class ScaffoldModule
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
     * Create a new ScaffoldModule instance.
     *
     * @param         $vendor
     * @param         $type
     * @param         $slug
     * @param         path
     */
    public function __construct($vendor, $type, $slug, $path)
    {
        $this->path = $path;
        $this->slug = $slug;
        $this->type = $type;
        $this->vendor = $vendor;
    }

    /**
     * Handle the command.
     *
     * @param Filesystem  $filesystem
     * @param Application $application
     *
     * @return string
     */
    public function handle(Filesystem $filesystem, Application $application)
    {
        $path = $this->path;
        $modulePath = __DIR__.'/../../../../../resources/assets/module';

        $data = $this->getTemplateData();

        /* Make module's folder */
        $filesystem->makeDirectory($path, 0755, true, true);

        /* Copy module template files */
        $filesystem->parseDirectory($modulePath.'/template', $path.'/', $data);

        return $path;
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
            'figlet_module_name' => Figlet::create($moduleName . ' Module', 'small' /* slant */),
            'module_name_lower' => strtolower($moduleName),
        ];
    }
}
