<?php

namespace Websemantics\BuilderExtension\Database\Migration;

/**
 * Class MigrationCreator.
 *
 * Extend the functionality of the Stream migrator
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 *
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc
 */
class MigrationCreator extends \Anomaly\Streams\Platform\Database\Migration\MigrationCreator
{
    /**
   * Get the migration stub file for when the fields option is set
   * along side the stream (double whammy).
   *
   * @param  string $table
   * @param  bool   $create
   *
   * @return string
   */
  protected function getStub($table, $create)
  {
      if ($this->input->getOption('fields') &&
          $this->input->getOption('stream')) {

          return $this->files->get(__DIR__.'/../../../resources/stubs/database/migrations/stream.stub');
      }

      return parent::getStub($table, $create);
  }

  /**
   * Populate the place-holders in the migration stub.
   *
   * @param  string $name
   * @param  string $stub
   * @param  string $table
   * @return string
   */
  protected function populateStub($name, $stub, $table)
  {
      $class  = $this->getClassName($name);

      $stream = $this->input->getOption('stream');
      $assignments = $this->getAssignments($this->input->getOption('fields'));

      return app('Anomaly\Streams\Platform\Support\Parser')
          ->parse($stub, compact('class', 'table', 'stream', 'assignments'));
  }

  /**
   * Parse the fields option for the stream $assignments,
   *
   * @param  string $name
   * @return string
   */
  protected function getAssignments($fields)
  {
      return '';
  }

}
