<?php

namespace Websemantics\BuilderExtension\Database\Migration;

/**
 * Class MigrationCreator.
 * TODO: Replace 'Anomaly\Streams\Platform\Support\Parser' with 'Websemantics\BuilderExtension\Support\TwigParser'
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
    * All posssible options for streams and assignments
    * notice that each option has a shorthand version (nice!!).
    *
    * @var array
    */
    protected $options = [
      'title_column' => 'tc',
      'type' => 't',
      'required' => 'r',
      'unique' => 'u',
      'searchable' => 's',
      'locked' => 'l',
      'translatable' => 'trans',
      'trashable' => 'trash',
      'sortable' => 'sort'
    ];

    /**
      * Options when appear in assignments gets inherited by the stream
      *
      * @var array
      */
    protected $inheritable = [
      'translatable',
      'trashable',
      'searchable'
    ];

  /**
    * Get an improved stream stub file with more power, yayy!
    *
    * @param  string $table
    * @param  bool   $create
    *
    * @return string
    */
    protected function getStub($table, $create){

      $path = __DIR__.'/../../../resources/stubs/database/migrations';

      if ($this->input->getOption('fields') && $this->input->getOption('stream')) {
        return $this->files->get("$path/fields.stub");
      }

      if ($this->input->getOption('stream')) {
        return $this->files->get("$path/stream.stub");
      }


      return parent::getStub($table, $create);
    }

  /**
    * Populate the place-holders in the migration stub.
    *
    * @param  string $name
    * @param  string $stub
    * @param  string $table
    *
    * @return string
    */
    protected function populateStub($name, $stub, $table){
      $class = $this->getClassName($name);

      list($stream, $fields, $assignments) =
        $this->getData($this->input->getOption('stream'));

      return app('Anomaly\Streams\Platform\Support\Parser')
      ->parse($stub, compact('class', 'table', 'stream', 'fields', 'assignments'));
    }

  /**
    * Cast option value to an approperiate data type (boolean, int etc).
    *
    * Support:
    * - boolean
    * - numeric
    *
    * @param  array $value, the option value
    *
    * @return array
    */
    protected function parseValue($value){

      if (in_array(strtolower($value), ['true', 'false'])) {
        return strtolower($value) === 'true' ? 'true' : 'false';
      } elseif (is_numeric($value)) {
        return $value + 0;
      }

      return "'$value'";
    }

  /**
    * Parse assignment / stream options and evaluate their values,
    *
    * @param  string $schema, assignment or stream schema
    *
    * @return array
    */
    protected function parseOptions($schema){

      // 1- Prepare options by extracting assignment/stream slug after spliting on ':'
      $options = explode(':', trim($schema));
      $slug = array_shift($options);

      // 2- preliminary, process user provided options
      $prelim = [];
      foreach ($options as $option) {
        $prelim[ /* remove brackets */ preg_replace("/\([^)]+\)/", '', $option)] =
        /* (value) */ $this->parseValue(preg_match('#\((.*?)\)#', $option, $match) ?
        $match[1] : /* if no value, assume as a switch */ 'true');
      }

      // 3- Get the full options list while considering shorthands, ignore otherwise,
      $processed = ['slug' => $this->parseValue($slug)];
      $keys = array_keys($prelim);
      foreach ($this->options as $option => $shorthand) {
        $processed[$option] = isset($prelim[$option]) ? $prelim[$option] :
        (isset($prelim[$shorthand]) ? $prelim[$shorthand] : /* not provided */ false);
      }

      return $processed;
    }

  /**
    * Get the stream, fields and assignments
    *
    * @param  string $stream, stream and assignments schema (comma seperated)
    *
    * @return array ($stream, $assignments)
    */

    protected function getData($stream){

      // 1- Unglue the stream from assignments (first occurrence)
      $fields = '';
      $assignments = explode(',', trim($stream));
      $stream = array_shift($assignments);

      // 2- Gets the stream options (title_column etc) from the schema
      $stream_options = $this->parseOptions($stream);

      // 3- Get assignments $definitions
      $definition = '';
      $s = count($assignments);
      $i = 0;

      foreach ($assignments as $assignment) {
        $definition .= $this->compileOptions($assignment =
          $this->parseOptions($assignment)) . ((++$i !== $s) ? "," : "");

        /* stream to inherit the earth, auh, no, some assignment options */
        foreach ($this->inheritable as $option) {
          if(isset($assignment[$option]) && $assignment[$option]){
            $stream_options[$option] = $assignment[$option];
          }
        }

        /* get the fields array populated */
        $type = !empty($assignment['type']) ? $assignment['type'] :
                                              "'anomaly.field_type.text'";

        $fields .= "\n\t\t\t\t".str_pad($assignment['slug'],
                   _config('config.padding')) . "=> $type,";

      }

      return [$this->compileOptions($stream_options, false).
                          "\n\t\t",  $fields. "\n\t\t", $definition. "\n\t\t"];
    }
  /**
    * Compile options into an associative array definition
    *
    * @param  array $options, a stream or assignment options
    * @param  boolean $wrap, wrap the compiled options into a slug entry
    *
    * @return string, an associative array, stringfied!
    */
    protected function compileOptions($options, $wrap = true){

      $definition = '';
      $slug = $options['slug'];

      // Filter-out all false values (untruths) & the slug if $wrap flag is true
      // And finally the type option (only used for fields)
      $options = (array_filter($options, function($v, $k) use($wrap){
        return $k !== 'type' && !($wrap && $k === 'slug') && !empty($v) ;
      }, ARRAY_FILTER_USE_BOTH));

      $i = 0;
      $s = count($options);

      foreach ($options as $option => $value) {
        $definition .= "\n\t\t\t\t".str_pad(($wrap?"\t\t":"") .
                       "'$option'", _config('config.padding')) .
                       "=> $value" . ((++$i !== $s) ? "," : "");
      }

      return $wrap ? "\n\t\t\t\t".str_pad("$slug", _config('config.padding') + 2) .
                        (!empty($definition) ? "=> [$definition\n\t\t\t\t]" : "")
                        : $definition;
    }
}
