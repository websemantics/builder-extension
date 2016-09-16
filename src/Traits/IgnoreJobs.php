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
     * Optionally turn it off and start showing these jobs some respect,
     *
     * @var  array  $ignore
     */
    protected $ignoreJobs = true;

    /**
     * Expected list of qualified classes names for jobs to ignore,
     * This is must be defined in the class using this Trait.
     *
     * @var  array  $ignore = [];
     */

    /**
    * Dispatch a job to its appropriate handler yet ignore some!
    *
    * @param  mixed  $job
    * @return mixed
    */
    protected function dispatch($job)
    {
     foreach ($this->ignore as $cls) {
         if ($this->ignoreJobs && is_a($job, $cls)) {
           return null; /* no hard feeling! */
         }
     }
     return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
    }
}
