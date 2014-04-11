<?php namespace Olissenberg\Queue;

use Illuminate\Support\ServiceProvider;

class ZendJobQueueServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->package('orlissenberg/laravel-zendserver-pushqueue');

        // 1. Need a route to marshal requests.
        // 2. Add the connector to the Queue facade.
        // 3. Add configuration to the app/config/queue.php
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}