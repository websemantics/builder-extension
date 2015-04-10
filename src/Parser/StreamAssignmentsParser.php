<?php namespace Websemantics\EntityBuilderExtension\Parser;

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;

/**
 * Class StreamAssignmentsParser
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class StreamAssignmentsParser
{

    /**
     * Return a list of comma seperated field slugs.
     *
     * @param  StreamInterface $stream
     * @return string
     */
    public function parse(StreamInterface $stream)
    {
        
        $slugs = [];

        $assignments = $stream->getAssignments();

        foreach ($assignments as $assignment) {
            $slugs[] = "'$assignment->getSlug()'";
        }

        return join(",", $slugs);

    }
}