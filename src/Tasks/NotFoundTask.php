<?php

namespace CFV\PhalconWorker\Tasks;

use Phalcon\Cli\Task;

class NotFoundTask extends Task
{
    public function missing()
    {
        $result = [
            "Class: " . self::class,
            "Method:" . __FUNCTION__,
            "Arguments: " . json_encode(func_get_args())
        ];

        print implode(PHP_EOL, $result);
    }
}
