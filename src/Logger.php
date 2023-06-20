<?php

namespace JuanchoSL\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    private $full_path;
    public function __construct(string $path, string $filename, bool $prepend_date = false)
    {
        if ($prepend_date) {
            $filename = date("Y-m-d") . '-' . $filename;
        }
        if (!file_exists($path)) {
            mkdir($path);
        }
        $this->full_path = $path . DIRECTORY_SEPARATOR . $filename;
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
        $string = date(DATE_ATOM) . " " . $level . " " . $message . PHP_EOL;
        file_put_contents($this->full_path, $string, FILE_APPEND | LOCK_EX);
    }
}