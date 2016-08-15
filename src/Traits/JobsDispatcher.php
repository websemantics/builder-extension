<?php namespace Websemantics\EntityBuilderExtension\Traits;

/**
 * Class JobsDispatcher.
 *
 * Override DispatchesJobs trait to avoid conflic with Entity Builder.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan Sagar <msagar@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

trait JobsDispatcher
{
  /**
   * List of jobs to skip.
   *
   * @var  array  $skip
   */
  protected $skip = [
    'Anomaly\Streams\Platform\Stream\Console\Command\WriteEntity' =>
                ['Collection', 'Controller', 'FormBuilder', 'Model', 'ModelInterface', 'Observer',
                 'Presenter', 'Repository', 'RepositoryInterface', 'Routes', 'TableBuilder'],

    'Anomaly\Streams\Platform\Addon\Console\Command\WriteAddon' =>
                ['Class', 'Composer', 'Lang', 'ServiceProvider']
    ];

   /**
    * Dispatch a job to its appropriate handler yet skip some!
    *
    * @param  mixed  $job
    * @return mixed
    */
   protected function dispatch($job)
   {
     foreach ($this->skip as $namespace => $jobs) {
       foreach ($jobs as $name) {
         if (is_a($job, $namespace . $name)) {
           return null;
         }
       }
     }
     return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
   }
}
