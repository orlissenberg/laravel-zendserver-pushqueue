<?php

require_once(__DIR__ . "/vendor/autoload.php");

use \Illuminate\Http\Request;
use \Illuminate\Queue\Capsule\Manager as Capsule;
use \Orlissenberg\Queue\Connectors\ZendJobQueueConnector;

$capsule = new Capsule;

/** @var \Illuminate\Queue\QueueManager $manager */
$manager = $capsule->getQueueManager();

$app = $capsule->getContainer();

$app->bind(
    "encrypter",
    function () {
        return new Illuminate\Encryption\Encrypter("test");
    }
);

$app->bind(
    "request",
    function () {
        return Request::createFromGlobals();
    }
);

// Add a new connector
$manager->extend(
    "zendserver",
    function () use ($app) {
        return new ZendJobQueueConnector($app['encrypter'], $app['request']);
    }
);

// Add a connection by the name of "zend"
$capsule->addConnection(
    [
        'driver' => 'zendserver',
        'options' => [],
        'callback-url' => 'http://jobqueue.dev/tests/receive.php',
    ],
    'zend'
);

// A driver requires configuration?

$capsule->setAsGlobal();