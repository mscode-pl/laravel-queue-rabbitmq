<?php

namespace MsCodePL\LaravelQueueRabbitMQ\Tests\Mocks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TestJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public mixed $i;

    public function __construct($i = 0)
    {
        $this->i = $i;
    }

    public function handle(): void
    {
        //
    }
}
