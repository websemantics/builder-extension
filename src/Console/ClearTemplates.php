<?php namespace Websemantics\BuilderExtension\Console;

use Colors\Color;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Websemantics\BuilderExtension\Traits\Registry;

/**
 * Class Clear Templates.
 *
 * Flush the Builder cache and clear all templates
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

class ClearTemplates extends Command
{

  use DispatchesJobs;
  use Registry;

  /**
   * The console command signature.
   *
   * @var string
   */

  protected $signature = 'builder:clear';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = "Clear Builder's cache and all stored templates";

  /**
   * Do it, now!
   */
  public function handle()
  {
    $this->logo();

//     // $this->output->writeln('<info>Title</info>');
//     // $this->output->writeln('<error>Error</error>');
//
//     $this->output->getFormatter()->setStyle('p', new OutputFormatterStyle('magenta', 'black'));
//     $this->output->getFormatter()->setStyle('t', new OutputFormatterStyle('white', 'black'));
// // 61269e
//     $this->output->writeln(_view('ascii.logo')->render());

    // $this->block(_view('ascii.logo')->render(), 'cyan');
    //
    // $this->output->warning('title');
    // $this->output->section('section');
    // $this->output->listing(['hi','hello','yes','no']);
    // highlight('green') === bg('green') === bg_green()
    // white() === fg('white')
    // echo $c('Hello World!')->white()->bold()->highlight('green') . PHP_EOL;

    die();
    // dd(app('commands'));

    // app('cache')->forget($key);
    $this->files->cleanDirectory($this->getBuilderPath());
    $this->info('Builder cache and all stored templates are cleared successfully');
  }
}
