<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;

/**
 * Class EntityNameParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class EntityNameParser
{

    /**
     * Return the entity name.
     *
     * @param  StreamInterface / string $stream
     * @return string
     */
    public function parse($stream)
    {
        return studly_case(str_singular(is_string($stream)? $stream :$stream->getSlug()));
    }
}
