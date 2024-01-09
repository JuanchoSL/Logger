<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

class Debugger
{

    private static array $loggers = [];
    private static $path;

    public static function init(string $path, $error_levels = E_ALL): string
    {
        self::initPaths($path);
        self::initExceptionHandler();
        self::initErrorHandler($error_levels);
        return self::$path;
    }

    public static function initPaths(string $path = null): string
    {
        if (is_null($path)) {
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'logs';
        }
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        self::$loggers = [
            'debug' => new Logger($path . DIRECTORY_SEPARATOR . 'debug.log'),
            'error' => new Logger($path . DIRECTORY_SEPARATOR . 'errors.log'),
            'info' => new Logger($path . DIRECTORY_SEPARATOR . 'access.log'),
        ];
        return self::$path = $path;
    }

    public static function initExceptionHandler(): string
    {
        if (empty(self::$loggers)) {
            self::initPaths();
        }
        set_exception_handler([Debugger::class, 'handlerException']);
        return self::$path;
    }

    public static function initErrorHandler($error_levels = E_ALL): string
    {
        if (empty(self::$loggers)) {
            self::initPaths();
        }
        set_error_handler([Debugger::class, 'handlerError'], $error_levels);
        return self::$path;
    }

    public static function __callStatic(string $method, array $args)
    {
        $logger = (array_key_exists($method, self::$loggers)) ? self::$loggers[$method] : self::$loggers['error'];
        call_user_func_array([$logger, $method], $args);
    }

    public static function handlerException(\Throwable $exception, array $context = []): void
    {
        $message = self::createMessage($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
        self::$loggers['error']->error($message, $context);
    }

    public static function handlerError(int $errno, string $errstr, string $errfile, int $errline, array $context = [])
    {
        $array_trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $array_trace = array_slice($array_trace, 2);
        $index = 0;
        foreach ($array_trace as $values) {
            $trace_str = "#{$index} ";
            if (array_key_exists('file', $values)) {
                $trace_str .= $values['file'];
            }
            if (array_key_exists('line', $values)) {
                $trace_str .= '(' . $values['line'] . ')';
            }
            $trace_str .= ": ";
            if (array_key_exists('class', $values)) {
                $trace_str .= $values['class'];
            }
            if (array_key_exists('type', $values)) {
                $trace_str .= $values['type'];
            }
            if (array_key_exists('function', $values)) {
                $trace_str .= $values['function'] . '()';
            }
            $trace[] = $trace_str;
            $index++;
        }
        $trace[] = "#{$index} {main}";
        $message = self::createMessage($errno, $errstr, $errfile, $errline, implode(PHP_EOL, $trace));
        switch ($errno) {
            case E_USER_DEPRECATED:
            case E_USER_NOTICE:
            case E_DEPRECATED:
            case E_NOTICE:
            //self::$loggers['info']->error($message, $context);
            //break;

            case E_USER_WARNING:
            case E_WARNING:
                self::$loggers['debug']->error($message, $context);
                break;

            case E_USER_ERROR:
            default:
                self::$loggers['error']->error($message, $context);
                break;
        }
    }

    protected static function createMessage(int $errno, string $errstr, string $errfile, int $errline, string $trace)
    {
        return implode(PHP_EOL, [
            implode(' ', [
                $errno,
                $errstr,
            ]),
            implode(' ', [
                'Origin: ',
                $errfile,
                '(' . $errline . ')',
            ]),
            $trace
        ]);
    }
    public static function testException()
    {
        throw new \Exception("This is a class exception", 400);
    }
    public static function testError()
    {
        trigger_error("This is a class tigger", E_USER_ERROR);
    }
}