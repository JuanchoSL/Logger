<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    private $full_path;
    public function __construct(string $path)
    {
        $this->full_path = $path;
        if (!is_dir($dir = pathinfo($this->full_path, PATHINFO_DIRNAME))) {
            mkdir($dir, 0777, true);
        }
    }
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $string = "[" . date(DATE_RFC2822) . " " . date_default_timezone_get() . "] [" . $level . "] ";
        $message = (string) $message;
        if (!empty($context)) {
            $trace = '';
            if (array_key_exists('exception', $context) && $context['exception'] instanceof \Throwable) {
                $trace = PHP_EOL . $context['exception']->getTraceAsString();
                unset($context['exception']);
            }
            foreach ($context as $key => $value) {
                if (strpos($message, "{" . $key . "}") !== false) {
                    $message = str_replace("{" . $key . "}", (string) $value, $message);
                    unset($context[$key]);
                }
            }
            $message .= $trace;
        }
        $string .= $message;

        if (!empty($context)) {
            $string .= PHP_EOL . json_encode($context, JSON_PRETTY_PRINT);
        }

        file_put_contents($this->full_path, $string . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}