<?php namespace Websemantics\BuilderExtension\Traits;

/**
 * Class IgnoreJobs.
 *
 * Override DispatchesJobs trait to avoid conflic with Builder extension.
 *
 * @link      http://websemantics.ca/ibuild
 * @link      http://ibuild.io
 * @author    WebSemantics, Inc. <info@websemantics.ca>
 * @author    Adnan M.Sagar, Phd. <adnan@websemantics.ca>
 * @copyright 2012-2016 Web Semantics, Inc.
 */

trait IgnoreJobs
{
    /**
     * Optionally turn off jobs ignorance, hahah ...
     *
     * @var  array  $skip
     */
    protected $ignoreJobs = true;

    /**
     * Expected list of jobs to skip to be defined in the class using this Trait,
     *
     * @var  array  $skip = [];
     */

    /**
    * Dispatch a job to its appropriate handler yet skip some!
    *
    * @param  mixed  $job
    * @return mixed
    */
    protected function dispatch($job)
    {
     foreach ($this->skip as $cls) {
         if ($this->ignoreJobs && is_a($job, $cls)) {
           return null;
         }
     }
     return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
    }
}
