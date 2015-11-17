<?php namespace Websemantics\EntityBuilderExtension\Console\Command;

use Websemantics\EntityBuilderExtension\Command\Traits\FileProcessor;
use Anomaly\Streams\Platform\Application\Application;
use Websemantics\EntityBuilderExtension\Console\MakeModule;
use Anomaly\Streams\Platform\Support\Parser;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ScaffoldModule
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Addon\Console\Command
 */
class ScaffoldModule implements SelfHandling
{
    use  FileProcessor;

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
     * The command instance.
     *
     * @var Command
     */
    private $command;

    /**
     * Create a new MakeAddonPaths instance.
     *
     * @param         $vendor
     * @param         $type
     * @param         $slug
     * @param Command $command
     */
    public function __construct($vendor, $type, $slug, MakeModule $command)
    {
        $this->slug    = $slug;
        $this->type    = $type;
        $this->vendor  = $vendor;
        $this->command = $command;
    }

    /**
     * Handle the command.
     *
     * @param Filesystem  $filesystem
     * @param Application $application
     * @return string
     */
    public function handle(Filesystem $filesystem, Application $application)
    {
        
        $reference    = $this->command->option('shared') ? 'shared' : $application->getReference();
        $path         = base_path("addons/{$reference}/{$this->vendor}/{$this->slug}-{$this->type}");
        $modulePath   = __DIR__ . '/../../../resources/assets/module';

        $data = $this->getTemplateData();

        /* Make module's folder */
        $filesystem->makeDirectory($path, 0755, true, true);

        /* Copy module template files */
        $filesystem->parseDirectory($modulePath."/template" , $path.'/', $data);

        return $path;
    }


    /**
     * Get the template data from a stream object.
     *
     * @param  Module $module
     * @param  StreamInterface $stream
     * @return array
     */
    protected function getTemplateData()
    {

        $moduleName  = studly_case($this->slug);
        $vendorName  = studly_case($this->vendor);

        return [
            'description'                   => '*Write description here*',
            'docblock'                      => ' *',
            'vendor_name'                   => $vendorName,
            'vendor_name_lower'             => strtolower($vendorName),
            'module_name'                   => $moduleName,
            'module_name_lower'             => strtolower($moduleName)
        ];

    }
}
