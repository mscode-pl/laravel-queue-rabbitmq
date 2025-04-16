<?php

namespace MsCodePL\LaravelQueueRabbitMQ\Queue;

use Illuminate\Support\Arr;
use MsCodePL\LaravelQueueRabbitMQ\Horizon\RabbitMQQueue as HorizonRabbitMQQueue;

class QueueFactory
{
    public static function make(array $config = []): RabbitMQQueue
    {
        $queueConfig = QueueConfigFactory::make($config);
        $worker = Arr::get($config, 'worker', 'default');

        if (strtolower($worker) == 'default') {
            return new RabbitMQQueue($queueConfig);
        }

        if (strtolower($worker) == 'horizon') {
            return new HorizonRabbitMQQueue($queueConfig);
        }

        return new $worker($queueConfig);
    }
}
