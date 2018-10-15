<?php

namespace CFV\PhalconWorker;

use Closure;
use GlobIterator;
use Phalcon\Annotations\Adapter\Memory as Annotations;
use Phalcon\Cli\Dispatcher;
use Phalcon\Cli\Router;
use Phalcon\Cli\Router\Route;
use Phalcon\Config;
use Phalcon\Config\Factory as ConfigFactory;
use Phalcon\Di as PhalconDi;
use Phalcon\Escaper;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Filter;
use Phalcon\Mvc\Model\Manager as ModelManager;
use Phalcon\Mvc\Model\MetaData\Memory as ModelsMetaData;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Security;
use SplFileInfo;

class Di extends PhalconDi
{
    public function __construct()
    {
        parent::__construct();
        Route::delimiter(':');
        $this->setShared('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setTaskSuffix('');
            $dispatcher->setActionSuffix('');
            return $dispatcher;
        });

        $this->setShared('router', function () {
            return new Router(false);
        });

        $this->setShared("eventsManager", EventsManager::class);
        $this->setShared("modelsManager", ModelManager::class);
        $this->setShared("modelsMetadata", ModelsMetaData::class);
        $this->setShared("filter", Filter::class);
        $this->setShared("escaper", Escaper::class);
        $this->setShared("annotations", Annotations::class);
        $this->setShared("security", Security::class);
        $this->setShared("transactionManager", TransactionManager::class);
        $this->setShared('config', $this->resolveConfigService());

        $this->setShared('jobs', function () {
            return new Router(false);
        });
    }

    /**
     * @return Closure
     */
    public function resolveConfigService(): Closure
    {
        return function (): Config {
            $files = new GlobIterator(__DIR__ . '/../config/*.php');
            $config = new Config();
            /**
             * @var SplFileInfo $item
             */
            foreach ($files as $item) {
                $basename = $item->getBasename('.' . $item->getExtension());
                $config[$basename] = ConfigFactory::load([
                    'filePath' => $item->getRealPath(),
                    'adapter' => $item->getExtension()
                ]);
            }

            return $config;
        };
    }
}
