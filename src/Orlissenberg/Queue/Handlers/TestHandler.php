<?php namespace Orlissenberg\Queue\Handlers;

class TestHandler
{
    public function handle($job, $data)
    {
        $handle = fopen("test.log", "a");
        if ($handle) {
            fwrite($handle, json_encode($data, true) . "\n");
            fclose($handle);
        }
    }
}