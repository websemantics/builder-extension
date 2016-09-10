<?php namespace Websemantics\BuilderExtension\Traits;

use Symfony\Component\Console\Helper\ProgressIndicator;

/**
  * Spinner trait.
  *
  * Keeps the user entertained while waiting,
  * Credits: https://github.com/helloIAmPau/node-spinner
  *
  * EXAMPLE:
  *
  *  $bar = $this->createProgressIndicator($spinnerTemplate);
  *  $bar->start('Loading ...');
  *
  *  while () {
  *     $bar->advance();
  *  }
  *
  *  $bar->finish('Done');
  *
  *
  * @link      http://websemantics.ca/ibuild
  * @link      http://ibuild.io
  * @author    WebSemantics, Inc. <info@websemantics.ca>
  * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
  * @copyright 2012-2016 Web Semantics, Inc.
 */

trait Spinner
{

  /**
   * A list of spinners.
   *
   * @var string
   */
  protected $spinners  = [
    "|/-\\",
    "🌍🌎🌏",
    "⠋⠙⠹⠸⠼⠴⠦⠧⠇⠏",
    "🌑🌒🌓🌔🌕🌝🌖🌗🌘🌚"
  ];


  /**
  * @param int $index, spinner template index
  * @param int $changeInterval, Change Interval in milliseconds
   *
   * @return ProgressIndicator
   */
  public function createProgressIndicator($index = null, $changeInterval = 10)
  {
      return new ProgressIndicator($this->output, null, $changeInterval, $this->template($index));
  }
  /**
   * @param int $index
   *
   * @return array
   */
  protected function template($index)
  {
    $index = $index ? : rand (0, count($this->spinners) - 1);
    preg_match_all('/./u', $this->spinners[$index], $spinner);
    return count($spinner) > 0 ? $spinner[0] : str_split($this->spinners[0]);
  }
}
