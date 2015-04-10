<?php namespace Websemantics\EntityBuilderExtension\Command;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class GenerateEntity
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class GenerateEntity
{

    /**
     * The stream interface.
     *
     * @var \Anomaly\Streams\Platform\Stream\Contract\StreamInterface
     */
    protected $stream;

    /**
     * The module class.
     *
     * @var \Anomaly\Streams\Platform\Addon\Module\Module
     */
    protected $module;

    /**
     * Create a new GenerateEntity instance.
     *
     * @param StreamInterface $stream
     * @param Module $module
     */
    public function __construct(Module $module, StreamInterface $stream)
    {
        $this->stream = $stream;
        $this->module = $module;
    }

    /**
     * Get the stream interface.
     *
     * @return StreamInterface
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Get the module instance.
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }
}
