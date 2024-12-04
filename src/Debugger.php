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

    private static Debugger $instance;

    private string $error_log_alias;

    public static function init(): static
    {
        return static::$instance = new Debugger();
    }

    public static function get(string $alias): ?LoggerInterface
    {
        if (empty(static::$instance)) {
            static::init();
        }
        return static::$instance->getLogger($alias);
    }

    public static function set(string $alias, LoggerInterface $logger): static
    {
        if (empty(static::$instance)) {
            $instance = static::init();
        } else {
            $instance = static::$instance;
        }
        return $instance->setLogger($alias, $logger);
    }

    public function getLogger(string $alias): ?LoggerInterface
    {
        return array_key_exists($alias, $this->loggers) ? $this->loggers[$alias] : null;
    }

    public function setLogger(string $alias, LoggerInterface $logger): static
    {
        $this->loggers[$alias] = $logger;
        return $this;
    }

    public function initFailuresHandler(string $error_log_alias, int $error_levels = E_ALL): static
    {
        $this->initExceptionHandler($error_log_alias);
        $this->initErrorHandler($error_log_alias, $error_levels);
        return $this;
    }

    protected function initExceptionHandler(string $error_log_alias): static
    {
        $this->error_log_alias = $error_log_alias;
        set_exception_handler([Debugger::class, 'handlerException']);
        return $this;
    }

    protected function initErrorHandler(string $error_log_alias, int $error_levels = E_ALL): static
    {
        $this->error_log_alias = $error_log_alias;
        error_reporting($error_levels);
        set_error_handler([Debugger::class, 'handlerError'], $error_levels);
        return $this;
    }

    /**
     * @param array<string,mixed> $context
     */
    public static function handlerException(\Throwable $exception, array $context = []): void
    {
        if (!empty(static::$instance)) {
            $context['exception'] = $exception;
            $logger = static::$instance;
            if (!empty($logger->error_log_alias)) {
                $logger->getLogger($logger->error_log_alias)?->error($exception, $context);
            }
        }
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