<?php

use Jeremeamia\SuperClosure\SerializableClosure;
use \Mockery as m;

class ZendJobTest extends PHPUnit_Framework_TestCase
{
    public function test_fire_with_referenced_handler()
    {
        $handler = m::mock("handler");
        $handler->shouldReceive("handle")->withArgs(['Orlissenberg\Queue\Jobs\ZendJob', "hi"]);

        $container = m::mock('Illuminate\Container\Container');
        $container->shouldReceive("make")->andReturn($handler);

        $zend = m::mock('Orlissenberg\Queue\ZendJobQueue');

        $json = json_encode(["job" => "handler@handle", "data" => "hi"]);
        $data = [
            "body" => $json
        ];

        $job = new \Orlissenberg\Queue\Jobs\ZendJob($container, $zend, (object)$data);
        $this->assertEquals($job->getRawBody(), $json);
        $job->fire();
    }

    public function test_fire_with_closure()
    {
        $container = m::mock('Illuminate\Container\Container');
        $container->shouldReceive("make")->andReturn(new IlluminateQueueClosure());

        $zend = m::mock('Orlissenberg\Queue\ZendJobQueue');

        $data = serialize(
            new SerializableClosure(function ($job) {
                $job->response = "Man, I never get a break!";
            })
        );

        $json = json_encode(["job" => "IlluminateQueueClosure", "data" => ["closure" => $data]]);

        $data = [
            "body" => $json
        ];

        $job = new \Orlissenberg\Queue\Jobs\ZendJob($container, $zend, (object)$data);
        $job->fire();
        $this->assertEquals("Man, I never get a break!", $job->response);
    }

    public function tearDown()
    {
        m::close();
    }
}