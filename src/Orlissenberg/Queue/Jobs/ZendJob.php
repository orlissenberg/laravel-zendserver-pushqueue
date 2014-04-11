<?php namespace Orlissenberg\Queue\Jobs;

use Illuminate\Queue\Jobs\Job;
use Illuminate\Container\Container;
use Orlissenberg\Queue\NotImplementedException;
use Orlissenberg\Queue\ZendJobQueue;

class ZendJob extends Job {

    /**
     * The Zend queue instance.
     *
     * @var \Orlissenberg\Queue\ZendJobQueue
     */
    protected $zend;

    /**
     * The ZendJobQueue message instance.
     *
     * @var object
     */
    protected $job;

    /**
     * Indicates if the message was a push message.
     *
     * @var bool
     */
    protected $pushed = false;

    /**
     * @param Container $container
     * @param ZendJobQueue $zend
     * @param $job
     * @param bool $pushed
     */
    public function __construct(Container $container,
        ZendJobQueue $zend,
        $job,
        $pushed = false)
    {
        $this->job = $job;
        $this->zend = $zend;
        $this->pushed = $pushed;
        $this->container = $container;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->getRawBody(), true));
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job->body;
    }

    /**
     * Delete the job from the queue.
     *
     * @throws \Orlissenberg\Queue\NotImplementedException
     * @return void
     */
    public function delete()
    {
        throw new NotImplementedException();
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int   $delay
     * @return void
     */
    public function release($delay = 0)
    {
        if ( ! $this->pushed) $this->delete();

        $this->recreateJob($delay);
    }

    /**
     * Release a pushed job back onto the queue.
     *
     * @param  int $delay
     * @throws \Orlissenberg\Queue\NotImplementedException
     */
    protected function recreateJob($delay)
    {
        throw new NotImplementedException();
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return array_get(json_decode($this->job->body, true), 'attempts', 1);
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job->id;
    }

    /**
     * Get the IoC container instance.
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the underlying Iron queue instance.
     *
     * @return \Illuminate\Queue\IronQueue
     */
    public function getIron()
    {
        return $this->zend;
    }

    /**
     * Get the underlying IronMQ job.
     *
     * @return array
     */
    public function getIronJob()
    {
        return $this->job;
    }

    /**
     * Get the name of the queue the job belongs to.
     *
     * @return string
     */
    public function getQueue()
    {
        return array_get(json_decode($this->job->body, true), 'queue');
    }

}
