<?php namespace Websemantics\EntityBuilderExtension\Filesystem;

use FilesystemIterator;
use Illuminate\Filesystem\Filesystem as FilesystemBase;
use Anomaly\Streams\Platform\Support\Parser;

/**
 * Class Filesystem
 *
 * New function that would parse the content of a file before copying it.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2015 Web Semantics, Inc.
 * @package   Websemantics\EntityBuilderExtension
 */

class Filesystem extends FilesystemBase{

    /**
     * The parser utility.
     *
     * @var Parser
     */
    protected $parser;

 		/**
     * Create a new Filesystem instance.
     *
     * @param Parser      $parser
     * @param Application $application
     */
    function __construct(Parser $parser)
    {
        $this->parser      = $parser;
    }

	/**
	 * Copy a directoy from one location to another.
	 *
	 * @param  string  $source
	 * @param  string  $destination
	 * @param  array   $data
	 * @param  int     $options
	 * @return bool
	 */
	public function parseDirectory($source, $destination, $data, $options = null)
	{ 
		if ( ! $this->isDirectory($source)) return false;

		$options = $options ?: FilesystemIterator::SKIP_DOTS;

		// If the destination destination does not actually exist, we will go ahead and
		// create it recursively, which just gets the destination prepared to copy
		// the files over. Once we make the source we'll proceed the copying.

		if ( ! $this->isDirectory($destination))
		{
			$this->makeDirectory($destination, 0777, true);
		}

		$items = new FilesystemIterator($source, $options);

		foreach ($items as $item)
		{

			// As we spin through items, we will check to see if the current file is actually
			// a source or a file. When it is actually a source we will need to call
			// back into this function recursively to keep copying these nested folders.
			$target = $destination.'/'.$this->parser->parse($item->getBasename(), $data);

			if ($item->isDir())
			{
				$path = $item->getPathname();

				if ( ! $this->parseDirectory($path, $target, $data, $options)) return false;
			}

			// If the current items is just a regular file, we will just parse its content 
			// and then copy this to the new location and keep looping. 
			else
			{
        $template = file_get_contents($item->getPathname());
        $this->put($target, $this->parser->parse($template, $data));
			}
		}

		return true;
	}

}