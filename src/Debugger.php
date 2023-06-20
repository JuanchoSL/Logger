<?php

namespace JuanchoSL\Logger;

class Debugger
{

    static private Logger $logger;

    public static function init(Logger $logger)
    {
        static::$logger = $logger;
    }

    public static function __callStatic($method, $args)
    {
        call_user_func_array([static::$logger, $method], $args);
    }
}