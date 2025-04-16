<?php

namespace MsCodePL\LaravelQueueRabbitMQ\Tests\Feature;

use Exception;
use MsCodePL\LaravelQueueRabbitMQ\Tests\Mocks\TestJob;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;

class QueueTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling([
            AMQPChannelClosedException::class, AMQPConnectionClosedException::class,
            AMQPProtocolChannelException::class,
        ]);
    }

    public function test_connection(): void
    {
        $this->assertInstanceOf(AMQPStreamConnection::class, $this->connection()->getChannel()->getConnection());
    }

    /**
     * @throws AMQPProtocolChannelException
     * @throws Exception
     */
    public function test_without_reconnect(): void
    {
        $queue = $this->connection('rabbitmq');

        $queue->push(new TestJob);
        sleep(1);
        $this->assertSame(1, $queue->size());

        // close connection
        $queue->getConnection()->close();
        $this->assertFalse($queue->getConnection()->isConnected());

        $this->expectException(AMQPChannelClosedException::class);
        $queue->push(new TestJob);
    }
}
