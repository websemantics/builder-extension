<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class RepositoryContentParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class RepositoryContentParser
{

    /**
     * Return the entity name.
     *
     * @param  StreamInterface $stream
     * @return string
     */
    public function parse(Module $module, StreamInterface $stream)
    {
        $destination = $module->getPath();
        $entityName  = studly_case(str_singular($stream->getSlug()));
        $file        = $destination."/builder/$entityName/$entityName"."Repository.php";

        return file_exists($file) ? file_get_contents($file) : '';

    }
}