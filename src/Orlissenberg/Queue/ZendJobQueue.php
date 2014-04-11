<?php namespace Orlissenberg\Queue;

use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Queue\Queue;
use Illuminate\Queue\QueueInterface;
use Orlissenberg\Queue\Jobs\ZendJob;

class ZendJobQueue extends Queue implements QueueInterface
{
    /**
     * The encrypter instance.
     *
     * @var \Illuminate\Encryption\Encrypter
     */
    protected $crypt;

    /**
     * The current request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    protected $callbackUrl;

    protected $options;

    public function getRequest() {
        return $this->request;
    }

    public function getCrypt() {
        return $this->crypt;
    }

    /**
     * Create a new ZendJobQueue instance.
     *
     * @param  \Illuminate\Encryption\Encrypter $crypt
     * @param \Illuminate\Http\Request $request
     * @param string $callbackUrl
     * @param array $options
     *
     * @return \Orlissenberg\Queue\ZendJobQueue
     */
    public function __construct(Encrypter $crypt, Request $request, $callbackUrl, array $options = [])
    {
        $this->crypt = $crypt;
        $this->request = $request;
        $this->callbackUrl = $callbackUrl;
        $this->options = $options;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string $job
     * @param  mixed $data
     * @param  string $queue
     * @return int
     */
    public function push($job, $data = '', $queue = null)
    {
        $payload = parent::createPayload($job, $data);
        $jobIndentifier = $this->pushRaw($payload, null, $this->options);
        return $jobIndentifier;
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string $payload
     * @param  string $queue
     * @param  array $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = array())
    {
        $payload = $this->crypt->encrypt($payload);

        /** @noinspection PhpUndefinedClassInspection */
        $queue = new \ZendJobQueue();
        /** @noinspection PhpUndefinedMethodInspection */
        $jobIdentifier = $queue->createHttpJob($this->callbackUrl, ["payload" => $payload], $options);
        return $jobIdentifier;
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTime|int $delay
     * @param  string $job
     * @param  mixed $data
     * @param  string $queue
     * @throws \Exception
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        throw new NotImplementedException();
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string $queue
     * @throws \Exception
     * @return \Illuminate\Queue\Jobs\Job|null
     */
    public function pop($queue = null)
    {
        throw new NotImplementedException();
    }

    /**
     * Marshal a push queue request and fire the job.
     *
     * See also:
     *  ZendJobQueue::setCurrentJobStatus
     *  ZendJobQueue::setCurrentJobStatus
     *  ZendJobQueue::OK
     *  ZendJobQueue::FAILED
     *
     * @throws \RuntimeException
     */
    public function marshal()
    {
        $this->createPushedZendJob($this->marshalPushedJob())->fire();

        return new Response('OK');
    }

    /**
     * Marshal out the pushed job and payload.
     *
     * @throws \Exception
     * @return object
     */
    protected function marshalPushedJob()
    {
        $id = $this->request->header('x-zend-job-id');
        $payload = $this->request->input("payload");

        if (empty($payload)) {
            throw new \Exception("Payload Not Found.");
        }

        $body = $this->crypt->decrypt($payload);

        return (object)[
            'id' => $id,
            'body' => $body,
            'pushed' => true,
        ];
    }

    /**
     * Create a new IronJob for a pushed job.
     *
     * @param  object $job
     * @return \Illuminate\Queue\Jobs\IronJob
     */
    protected function createPushedZendJob($job)
    {
        return new ZendJob($this->container, $this, $job, true);
    }
}