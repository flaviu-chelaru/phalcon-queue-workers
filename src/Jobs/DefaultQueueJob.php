<?php

namespace CFV\PhalconWorker\Jobs;

use PhpAmqpLib\Message\AMQPMessage;

class DefaultQueueJob
{
    public function run(AMQPMessage $message)
    {
        var_dump($message);
        die;
    }
}
