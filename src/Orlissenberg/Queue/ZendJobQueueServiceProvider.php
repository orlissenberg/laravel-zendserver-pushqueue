<?php namespace Orlissenberg\Queue;

use Illuminate\Support\ServiceProvider;
use Orlissenberg\Queue\ZendJobQueue;

class ZendJobQueueServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->package('orlissenberg/laravel-zendserver-pushqueue');

        // 1. Need a route to marshal requests.
        \Route::any(
            "/queue/zendserver",
            function () {
                /** @var ZendJobQueue $connection */
                $connection = \Queue::connection("zendjobqueue");
                $connection->marshal();
            }
        );

        // 2. Add the connector to the Queue facade.
        \Queue::addConnector(
            "zendserver",
            function () {
                return app()->make("\\Orlissenberg\\Queue\\Connectors\\ZendJobQueueConnector");
            }
        );

        // 3. Add configuration to the app/config/queue.php
        /*
        'zendjob' => [
            'driver' => 'zendserver',
            'options' => [],
            'callback-url' => '/queue/zendserver',
        ],
        */

        // 4. Enable the test route (optional for testing only)
        /*
        \Route::get(
            "/queue/zendtest",
            function () {
                $connection = \Queue::connection("zendjobqueue");
                $connection->push("\\Orlissenberg\\Queue\\Handlers\\TestHandler@handle", ["laravel4" => "rocks"]);

                return "Job queued.";
            }
        );
        */
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }
}