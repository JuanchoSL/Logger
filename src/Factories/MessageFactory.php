<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Factories;

class MessageFactory implements \Stringable
{
    private \Throwable $message;
    public static function make(\Throwable $exception): MessageFactory
    {
        return new self($exception);
    }

    public function __construct(\Throwable $throwable)
    {
        $this->message = $throwable;
    }

    public function __toString(): string
    {
        return implode(PHP_EOL, [
            implode(' ', [
                $this->message->getCode() . ":",
                $this->message->getMessage(),
            ]),
            implode(' ', [
                'Origin:',
                $this->message->getFile(),
                '(' . $this->message->getLine() . ')',
            ])
        ]);

    }
}