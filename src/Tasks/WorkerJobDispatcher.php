<?php

namespace CFV\PhalconWorker\Tasks;

use CFV\PhalconWorker\Application;
use Phalcon\Cli\Router;
use Phalcon\Cli\Task;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RunWorker
 *
 * @property Application $console
 * @property DiInterface $di
 * @property Router $jobs
 */
class WorkerJobDispatcher extends Task
{
    /** @var AMQPStreamConnection */
    private $connection;

    /**
     * @return void
     */
    public function onConstruct()
    {
        /** @var Config $config */
        $config = $this->di->get('config');
        $connectionArguments = [
            $config->path('queue.host'),
            $config->path('queue.port'),
            $config->path('queue.username'),
            $config->path('queue.password'),
            $config->path('queue.vhost'),
        ];
        $this->connection = new AMQPStreamConnection(...$connectionArguments);
    }

    /**
     * @param mixed ...$rawArgs
     *
     * @throws \PhpAmqpLib\Exception\AMQPOutOfBoundsException
     * @throws \PhpAmqpLib\Exception\AMQPRuntimeException
     */
    public function handle(...$rawArgs)
    {
        array_shift($rawArgs);
        $args = array_shift($rawArgs);

        $channel = $this->connection->channel();
        $channel->basic_consume($args['queue'] ?? 'workers', '', false, true, false, false, function (
            AMQPMessage $message
        ) {
            $type = $message->has('type') ? $message->get('type') : '';
            fwrite(STDOUT, ' [x] Received ' . $message->body . PHP_EOL);
            fwrite(STDOUT, ' [x] Received type ' . $type . PHP_EOL);

            /** @var Router $route */
            $this->jobs->handle($type);
            $matchedRoute = $this->jobs->getMatchedRoute();

            if ($matchedRoute instanceof Router\Route) {
                $callback = [$matchedRoute->getPaths()['task'], $matchedRoute->getPaths()['action']];
                try {
                    call_user_func($callback, $message);
                } catch (\Exception $exception) {
                    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
                }
            }
        });

        fwrite(STDOUT, ' [x] Starting listening for incoming connections on pid #' . getmypid() . PHP_EOL);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}
