<?php

namespace CFV\PhalconWorker;

use Phalcon\Cli\Console;
use Phalcon\DiInterface;

class Application extends Console
{
    public function __construct(DiInterface $dependencyInjector = null)
    {
        parent::__construct($dependencyInjector);
        if ($dependencyInjector instanceof DiInterface) {
            $dependencyInjector->setShared('console', $this);
        }
    }
}
