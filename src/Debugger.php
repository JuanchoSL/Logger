<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

use ErrorException;
use Psr\Log\LoggerInterface;

class Debugger
{
    /**
     * @var array<string, LoggerInterface> $loggers
     */
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
        if (empty ($logger)) {
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

    public function initErrorHandler(string $error_log_alias, int $error_levels = E_ALL): self
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
        if (empty (self::$instance) || !is_null($path)) {
            if (is_null($path)) {
                $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'logs';
            }
            self::$instance = new Debugger($path);
        }
        return self::$instance;
    }

    /**
     * @param array<string,mixed> $context
     */
    public static function handlerException(\Throwable $exception, array $context = []): void
    {
        //$message = self::createMessage($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
        //$message = MessageFactory::make($exception);
        $context['exception'] = $exception;
        $logger = self::getInstance();
        $logger->getLogger($logger->error_log_alias)?->error($exception, $context);
    }

    /**
     * @param array<string,mixed> $context
     */
    public static function handlerError(int $errno, string $errstr, string $errfile, int $errline, array $context = []): bool
    {
        if (!(error_reporting() & $errno)) {
            return false;
        }
        $error = new ErrorException($errstr, $errno, $errno, $errfile, $errline);
        self::handlerException($error, $context);
        return true;
    }

    public static function testException(): void
    {
        throw new \Exception("This is a class exception", 400);
    }
    public static function testError(): void
    {
        trigger_error("This is a class tigger", E_USER_ERROR);
    }
}