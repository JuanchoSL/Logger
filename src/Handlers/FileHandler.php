<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Handlers;

class FileHandler extends AbstractHandler
{

    private string $full_path;

    public function __construct(string $full_path)
    {
        $this->full_path = $full_path;
        if (!is_dir($dir = pathinfo($this->full_path, PATHINFO_DIRNAME))) {
            mkdir($dir, 0666, true);
        }
    }

    public function save(): bool
    {
        $string = "[" . date($this->timeformat, $this->timestamp) . " " . date_default_timezone_get() . "] [" . $this->level . "] ";
        $message = $this->parseMessage();
        if (!empty($this->context)) {
            $trace = '';
            if (array_key_exists('exception', $this->context) && $this->context['exception'] instanceof \Throwable) {
                $trace = PHP_EOL . $this->context['exception']->getTraceAsString() . PHP_EOL;
                unset($this->context['exception']);
            }
            $message .= $trace;
        }
        $string .= $message;

        if (!empty($this->context)) {
            $string .= PHP_EOL . json_encode($this->context, JSON_PRETTY_PRINT) . PHP_EOL;
        }

        return file_put_contents($this->full_path, $string . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
    }
}