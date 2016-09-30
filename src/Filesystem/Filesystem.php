<?php namespace Websemantics\BuilderExtension\Filesystem;

use FilesystemIterator;
use Websemantics\BuilderExtension\Support\TwigParser;

/**
 * Class Filesystem
 *
 * New function that would parse the content of a file before copying it.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension
 */

class Filesystem extends \Illuminate\Filesystem\Filesystem
{
    /**
     * The parser utility.
     *
     * @var TwigParser
     */
    protected $parser;

    /**
     * A list of files suffixes that should not be overwritten
     *
     * @var Parser
     */
    protected $avoid_overwrite = [];

 		/**
     * Create a new Filesystem instance.
     *
     * @param Parser      $parser
     * @param Application $application
     */
    function __construct(TwigParser $parser)
    {
        $this->parser      		 = $parser;
    }

    /**
     * Set the avoid overwite list of files,
     *
     * @param arry $avoid_overwrite, a list of files suffixes that should
     *                               not be overwritten
     */
		public function setAvoidOverwrite($avoid_overwrite){

        $this->avoid_overwrite = $avoid_overwrite;
		}

		/**
		 * Write the contents of a file. Avoid the ones listed in $avoid_overwrite
     * attribute or if sent as an argument
		 *
		 * @param  string  $path
		 * @param  string  $contents
		 * @param  bool  $lock
		 * @return int
		 */
		public function put($path, $contents, $avoid = false, $lock = false)
		{

			foreach ($this->avoid_overwrite as $file) {
					if($avoid ||  $avoid = ends_with($path, $file)){
						break;
					}
			}

      /* Write the file if it does not already exists or
      if was not guraded from overwrite */

      if(!$this->exists($path) || !$avoid) {
        return parent::put($path, $contents, $lock);
      }
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
			if (!$this->isDirectory($source)) return false;

			$options = $options ?: FilesystemIterator::SKIP_DOTS;

			// If the destination destination does not actually exist, we will go ahead and
			// create it recursively, which just gets the destination prepared to copy
			// the files over. Once we make the source we'll proceed the copying.

			if (!$this->isDirectory($destination)){
				$this->makeDirectory($destination, 0777, true);
			}

			$items = new FilesystemIterator($source, $options);

			foreach ($items as $item){
				// As we spin through items, we will check to see if the current file is actually
				// a source or a file. When it is actually a source we will need to call
				// back into this function recursively to keep copying these nested folders.
				$target = $destination.'/'.$this->parser->parse($item->getBasename(), $data);

				if ($item->isDir()){
					if (!$this->parseDirectory($item->getPathname(), $target, $data, $options)){
              return false;
          }
				} else {
          // If the current items is just a regular text file, we will just parse its content
  				// and then copy this to the new location and keep looping, otherwise, just copy file.
          if(substr(finfo_file(finfo_open(FILEINFO_MIME_TYPE), $item->getPathname()), 0, 4) === 'text'){
            $this->put($target, $this->parser->parse(file_get_contents($item->getPathname()), $data));
          } else {
            $this->copy($item->getPathname(), $target);
          }
				}
			}
			return true;
		}
}
