<?php

namespace Websemantics\EntityBuilderExtension\Stream\Console;

/**
 * Class Make.
 *
 * Override core Make command to avoid conflic with Entity Classes Generated
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

class Make extends \Anomaly\Streams\Platform\Stream\Console\Make
{

  /**
   * Dispatched jobs to skip.
   *
   * @var  array  $skip
   */
  protected $skip = ['Collection', 'Controller', 'FormBuilder', 'Model',
                     'ModelInterface', 'Observer', 'Presenter', 'Repository',
                     'RepositoryInterface', 'Routes', 'TableBuilder'];

  /**
   * Dispatch a job to its appropriate handler.
   *
   * @param  mixed  $job
   * @return mixed
   */
  protected function dispatch($job)
  {
    foreach ($this->skip as $className) {
      if (is_a($job, 'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntity' . $className)) {
        return null;
      }
    }
    return parent::dispatch($job);
  }
}
