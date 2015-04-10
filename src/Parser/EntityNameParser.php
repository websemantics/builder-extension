<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;

/**
 * Class EntityNameParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class EntityNameParser
{

    /**
     * Return the entity name.
     *
     * @param  StreamInterface $stream
     * @return string
     */
    public function parse(StreamInterface $stream)
    {
        return studly_case(str_singular($stream->getSlug()));
    }
}