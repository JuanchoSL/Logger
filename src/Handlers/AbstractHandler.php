<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Handlers;

use JuanchoSL\Logger\Contracts\HandlerInterface;
use JuanchoSL\Logger\Factories\MessageFactory;

abstract class AbstractHandler implements HandlerInterface
{

    protected int $timestamp;
    protected string $timeformat;
    protected \Stringable|string $message;
    protected string $level;
    protected array $context = [];

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function setTimeFormat(string $timeformat): self
    {
        $this->timeformat = $timeformat;
        return $this;
    }

    public function setMessage(\Stringable|string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    protected function parseMessage(): string
    {
        if ($this->message instanceof \Throwable) {
            if (!array_key_exists('exception', $this->context)) {
                $this->context['exception'] = $this->message;
            }
            $this->message = MessageFactory::make($this->message);
        }
        $message = (string) $this->message;
        foreach ($this->context as $key => $value) {
            if (strpos($message, "{" . $key . "}") !== false) {
                $message = str_replace("{" . $key . "}", (string) $value, $message);
                unset($this->context[$key]);
            }
        }
        return $message;
    }
}