<?php

namespace MsCodePL\LaravelQueueRabbitMQ\Queue\Connectors;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\WorkerStopping;
use MsCodePL\LaravelQueueRabbitMQ\Horizon\Listeners\RabbitMQFailedEvent;
use MsCodePL\LaravelQueueRabbitMQ\Horizon\RabbitMQQueue as HorizonRabbitMQQueue;
use MsCodePL\LaravelQueueRabbitMQ\Queue\Connection\ConnectionFactory;
use MsCodePL\LaravelQueueRabbitMQ\Queue\QueueFactory;
use MsCodePL\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

class RabbitMQConnector implements ConnectorInterface
{
    protected Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Establish a queue connection.
     *
     * @return RabbitMQQueue
     *
     * @throws Exception
     */
    public function connect(array $config): Queue
    {
        $connection = ConnectionFactory::make($config);

        $queue = QueueFactory::make($config)->setConnection($connection);

        if ($queue instanceof HorizonRabbitMQQueue) {
            $this->dispatcher->listen(JobFailed::class, RabbitMQFailedEvent::class);
        }

        $this->dispatcher->listen(WorkerStopping::class, static function () use ($queue): void {
            $queue->close();
        });

        return $queue;
    }
}
