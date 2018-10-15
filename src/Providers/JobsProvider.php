<?php

namespace CFV\PhalconWorker\Providers;

use CFV\PhalconWorker\Jobs\DefaultQueueJob;
use Phalcon\Cli\Router;
use Phalcon\Di\ServiceProviderInterface;

class JobsProvider implements ServiceProviderInterface
{
    public function register(\Phalcon\DiInterface $di)
    {
        /** @var Router $router */
        $router = $di->get("jobs");
        $router->add("default:test", [
            "task" => DefaultQueueJob::class,
            "action" => "run"
        ]);
    }
}
