<?php

require_once(__DIR__ . "/../autoload.php");

use \Orlissenberg\Queue\ZendJobQueue;
use \Illuminate\Queue\Capsule\Manager as Capsule;

/** @var ZendJobQueue $queue */
$queue = Capsule::connection("zend");

// Mimic input data.
if (false) {
    $secret = $queue->getCrypt()->encrypt(
        json_encode(
            ["job" => "\\Orlissenberg\\Queue\\Handlers\\TestHandler@handle", "data" => ["foo" => "bar"]]
        )
    );
    $queue->getRequest()->merge(["payload" => $secret]);
}

// Test encoded payload (copied from the Zend Server dashboard!)
if (false) {
    $queue->getRequest()->merge(
        array (
            'payload' => 'eyJpdiI6IjhBbmhBRDNWb0d4QVJHZFFCRzdZZ1F5MklETVJEMnFoSXlEeUl1MmVwTjA9IiwidmFsdWUiOiJuY08yOEZxRFdZc2EzcmttanN4RGhSMitsWDl1NWJEeFpBd01CNEhvckRCUTFSZllReVlhRHcwTzQ1VkVnV1RDSjljc3o0aWVQd2dNSFlsemptYWV6VXM1bVhuMWJHbTNQSFU4c2ZHbDNwSnBGUHpxc1d5S1dJNnlTSFppMDVnSyIsIm1hYyI6IjFiMmNjNDhlMWUyMjcwYjY1Y2UzMzdjYTUyNmUyMmE5ZTQ4YjhiNzhiMzY1MGMzZDdlMWJkMmM1MzFhNDMyZjQifQ==',
        )
    );
}

$response = $queue->marshal();
echo $response->getContent();