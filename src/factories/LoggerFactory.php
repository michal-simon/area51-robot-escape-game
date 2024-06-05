<?php

namespace Robot\Factories;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class LoggerFactory
{
    public static function createInstance(): Logger
    {
        $log = new Logger('robot');
        $log->pushHandler(new StreamHandler('php://stdout', Level::Debug));

        return $log;
    }
}
