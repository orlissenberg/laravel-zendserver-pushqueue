<?php

namespace spec\Orlissenberg\Queue\Jobs;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Illuminate\Container\Container;
use Orlissenberg\Queue\ZendJobQueue;

class ZendJobSpec extends ObjectBehavior
{
    function it_is_initializable(Container $container, ZendJobQueue $queue)
    {
        $this->beConstructedWith($container, $queue, "job");
        $this->shouldHaveType('Orlissenberg\Queue\Jobs\ZendJob');
    }
}
