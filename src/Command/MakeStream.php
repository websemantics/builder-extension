<?php namespace Websemantics\BuilderExtension\Command;

use Illuminate\Filesystem\Filesystem;

/**
 * Class MakeStream.
 *
 * Currently, creates an empty seeder file at 'path-to-addon/resources/seeders/'
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 * @package   Websemantics\BuilderExtension\Anomaly\Stream\Console\Command
 */

class MakeStream
{
    /**
     * The entity name lower case
     *
     * @var string
     */
    private $entity;

    /**
     * The addon path.
     *
     * @var string
     */
    private $path;

    /**
     * Create a new MakeStream instance.
     *
     * @param         $slug, stream slug
     * @param         path
     */
    public function __construct($slug, $path)
    {

      $this->entity = strtolower(str_singular($slug));
      $this->path = $path;
    }

    /**
     * Handle the command, plant a seed,
     *
     * @param Filesystem  $files
     *
     * @return string
     */
    public function handle(Filesystem $files)
    {
      $seeder = $this->path . '/resources/seeders/' . $this->entity . '.php';

      if (!$files->isDirectory($parent = dirname($seeder))) {
          $files->makeDirectory($parent, 0777, true);
      }

      $files->put($seeder, '', true);
    }
}
