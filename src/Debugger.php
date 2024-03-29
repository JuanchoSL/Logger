<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

use ErrorException;
use Psr\Log\LoggerInterface;

class Debugger
{

    private array $loggers = [];

    private string $path;

    private static Debugger $instance;

    private string $error_log_alias;

    protected function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getLogger(string $alias): ?LoggerInterface
    {
        return array_key_exists($alias, $this->loggers) ? $this->loggers[$alias] : null;
    }

    public function setLogger(string $alias, ?LoggerInterface $logger = null): self
    {
        if (empty($logger)) {
            $logger = new Logger($this->path . DIRECTORY_SEPARATOR . $alias . '.log');
        }
        $this->loggers[$alias] = $logger;
        return $this;
    }

    public function initExceptionHandler(string $error_log_alias): self
    {
        if (!array_key_exists($error_log_alias, $this->loggers)) {
            $this->setLogger($error_log_alias);
        }
        $this->error_log_alias = $error_log_alias;
        set_exception_handler([Debugger::class, 'handlerException']);
        return $this;
    }

    public function initErrorHandler(string $error_log_alias, $error_levels = E_ALL): self
    {
        if (!array_key_exists($error_log_alias, $this->loggers)) {
            $this->setLogger($error_log_alias);
        }
        $this->error_log_alias = $error_log_alias;
        error_reporting($error_levels);
        set_error_handler([Debugger::class, 'handlerError'], $error_levels);
        return $this;
    }

    public static function getInstance(string $path = null): Debugger
    {
        if (empty(self::$instance)) {
            if (is_null($path)) {
                $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'logs';
            }
            self::$instance = new Debugger($path);
        }
        return self::$instance;
    }
    
    public static function handlerException(\Throwable $exception, array $context = []): void
    {
        $message = self::createMessage($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
        $context['exception'] = $exception;
        $logger = self::getInstance();
        $logger->getLogger($logger->error_log_alias)->error($message, $context);
    }

    public static function handlerError(int $errno, string $errstr, string $errfile, int $errline, array $context = [])
    {
        //if (0 === error_reporting()) { return false;}
        if (!(error_reporting() & $errno)) {
            return false;
        }
        $error = new ErrorException($errstr, $errno, $errno, $errfile, $errline);
        self::handlerException($error, $context);
        return true;
        /*  
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
        */
    }

    protected static function createMessage(int $errno, string $errstr, string $errfile, int $errline, string $trace = '')
    {
        $return = implode(PHP_EOL, [
            implode(' ', [
                $errno . ":",
                $errstr,
            ]),
            implode(' ', [
                'Origin:',
                $errfile,
                '(' . $errline . ')',
            ])
        ]);
        if (!empty($trace)) {
            $return .= PHP_EOL . $trace;
        }
        return $return;
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