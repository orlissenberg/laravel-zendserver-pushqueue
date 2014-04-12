#Laravel 4 - Zend Server (>= 6.1) Push Queue

[![Build Status](https://travis-ci.org/orlissenberg/laravel-zendserver-pushqueue.svg?branch=master)](https://travis-ci.org/orlissenberg/laravel-zendserver-pushqueue)

[Zend Server Job Queue Documentation](http://files.zend.com/help/Zend-Server-6/content/jobs_component.htm)

Add the Service Provider to your config/app.php

    'Olissenberg\Queue\ZendJobQueueServiceProvider',

Add configuration to the config/queue.php

    'zendjobqueue' => [
        'driver' => 'zendserver',
        'options' => [],
        'callback-url' => '/queue/zendserver',
    ],

Should work just like Iron IO push queues from that point on, note that it only works with commercial versions of Zend Server.

Notes and ToDo's

- Don't use large payloads, performance will suffer (see documentation).
- Zend Server 6.3 has https support, we had issues with earlier versions.
- The timed/delayed jobs are not implemented yet.
- Probably 75% copied/reused from IronQueue.
- ToDo: More tests/coverage!

MIT License, enjoy!

