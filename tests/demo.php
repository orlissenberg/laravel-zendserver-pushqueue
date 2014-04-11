<?php

require_once(__DIR__ . "/../autoload.php");

use \Orlissenberg\Queue\ZendJobQueue;
use \Illuminate\Queue\Capsule\Manager as Capsule;

/** @var ZendJobQueue $queue */
$queue = Capsule::connection("zend");

$queue->push("\\Orlissenberg\\Queue\\Handlers\\TestHandler@handle", ["test" => "data"], null);

$queue->push(
    function () {
        $handle = fopen("test.log", "a");
        if ($handle) {
            fwrite($handle, "Oh look it's a super closure.");
            fclose($handle);
        }
    },
    ["test" => "this should work too"]
);

echo 'Jobs queued up!';