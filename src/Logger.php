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
        $this->handler->setLevel($level)->setMessage($message)->setContext($context)->setTimestamp(time())->setTimeFormat($this->time_format)->save();
    }
}