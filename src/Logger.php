<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    private $full_path;
    public function __construct(string $path)
    {
        $this->full_path = $path;
        if (!is_dir($dir = pathinfo($this->full_path, PATHINFO_DIRNAME))) {
            mkdir($dir, 0777, true);
        }
    }
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }
    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }
    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }
    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }
    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }
    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }
    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $string = date(DATE_ATOM) . " [" . $level . "]: " . $message;
        if (!empty($context)) {
            $string .= PHP_EOL .json_encode($context, JSON_PRETTY_PRINT);
        }
        file_put_contents($this->full_path, $string . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}