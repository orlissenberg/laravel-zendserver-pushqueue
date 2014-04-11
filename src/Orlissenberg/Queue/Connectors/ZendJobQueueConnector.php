<?php namespace Orlissenberg\Queue\Connectors;

use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Orlissenberg\Queue\ZendJobQueue;

class ZendJobQueueConnector implements ConnectorInterface
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
     * @var \Illuminate\Http\Request;
     */
    protected $request;

    /**
     * Create a new Iron connector instance.
     *
     * @param  \Illuminate\Encryption\Encrypter $crypt
     * @param \Illuminate\Http\Request $request
     *
     * @return \Orlissenberg\Queue\Connectors\ZendJobQueueConnector
     */
    public function __construct(Encrypter $crypt, Request $request)
    {
        $this->crypt = $crypt;
        $this->request = $request;
    }

    /**
     * Establish a queue connection.
     *
     * @param  array $config
     * @return \Illuminate\Queue\QueueInterface
     */
    public function connect(array $config)
    {
        return new ZendJobQueue(
            $this->crypt,
            $this->request,
            $config["callback-url"],
            $config["options"]);
    }
}