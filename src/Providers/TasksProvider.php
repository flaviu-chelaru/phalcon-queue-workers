<?php

namespace CFV\PhalconWorker\Providers;

use CFV\PhalconWorker\Tasks\WorkerJobDispatcher;
use CFV\PhalconWorker\Tasks\NotFoundTask;
use Phalcon\Cli\Router;
use Phalcon\Di\ServiceProviderInterface;

class TasksProvider implements ServiceProviderInterface
{
    public function register(\Phalcon\DiInterface $di)
    {
        /** @var Router $router */
        $router = $di->get("router");
        $router->setDefaults([
            "task" => NotFoundTask::class,
            "action" => "missing"
        ]);

        $router->add("workers:start", [
            "task" => WorkerJobDispatcher::class,
            "action" => "handle"
        ]);
    }
}
