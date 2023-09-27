<?php

namespace JuanchoSL\Logger;

class Debugger
{

    private static $loggers = [];

    public static function init(string $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        static::$loggers = [
            'debug' => new Logger($path . DIRECTORY_SEPARATOR . 'debug.log'),
            'error' => new Logger($path . DIRECTORY_SEPARATOR . 'errors.log'),
            'info' => new Logger($path . DIRECTORY_SEPARATOR . 'access.log'),
        ];
    }

    public static function __callStatic($method, $args)
    {
        $logger = (array_key_exists($method, static::$loggers)) ? static::$loggers[$method] : static::$loggers['error'];
        call_user_func_array([$logger, $method], $args);
    }

    public static function exception(\Throwable $exception, array $context = []): void
    {
        $message = implode(PHP_EOL, [
            implode(' ', [
                $exception->getCode(),
                $exception->getMessage(),
            ]),
            implode(' ', [
                'Origin: ',
                $exception->getFile(),
                '(' . $exception->getLine() . ')',
            ]),
            $exception->getTraceAsString()
        ]);
        static::$loggers['error']->error($message, $context);
    }
}