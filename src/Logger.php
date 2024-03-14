<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

use JuanchoSL\Logger\Contracts\HandlerInterface;
use JuanchoSL\Logger\Handlers\FileHandler;
use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    private $full_path;
    private $time_format = DATE_RFC2822;
    private $handler;
    public function __construct(HandlerInterface|string $path)
    {
        if (is_string($path)) {
            /*$this->full_path = $path;
            if (!is_dir($dir = pathinfo($this->full_path, PATHINFO_DIRNAME))) {
                mkdir($dir, 0666, true);
            }*/
            $this->handler = new FileHandler($path);
            //trigger_error("Use Log data handlers instead full path for logs", E_USER_DEPRECATED);
        } else {
            $this->handler = $path;
        }
    }

    public function setTimeFormat(string $time_format): self
    {
        $this->time_format = $time_format;
        return $this;
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        /*
        if (isset($this->full_path)) {
            $string = "[" . date($this->time_format) . " " . date_default_timezone_get() . "] [" . $level . "] ";
            $message = (string) $message;
            if (!empty($context)) {
                $trace = '';
                if (array_key_exists('exception', $context) && $context['exception'] instanceof \Throwable) {
                    $trace = PHP_EOL . $context['exception']->getTraceAsString() . PHP_EOL;
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
                $string .= PHP_EOL . json_encode($context, JSON_PRETTY_PRINT) . PHP_EOL;
            }

            file_put_contents($this->full_path, $string . PHP_EOL, FILE_APPEND | LOCK_EX);
        } else {
        }*/
        $this->handler->setLevel($level)->setMessage((string) $message)->setContext($context)->setTimestamp(time())->setTimeFormat($this->time_format)->save();
    }
}