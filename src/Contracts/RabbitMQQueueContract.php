<?php

namespace MsCodePL\LaravelQueueRabbitMQ\Contracts;

use MsCodePL\LaravelQueueRabbitMQ\Queue\QueueConfig;
use MsCodePL\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;
use PhpAmqpLib\Connection\AbstractConnection;

interface RabbitMQQueueContract
{
    public function __construct(QueueConfig $config);

    public function setConnection(AbstractConnection $connection): RabbitMQQueue;
}
