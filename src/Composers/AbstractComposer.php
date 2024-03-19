<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Composers;

use JuanchoSL\Logger\Contracts\LogComposerInterface;

abstract class AbstractComposer implements LogComposerInterface
{

    protected string $time_mark;
    protected string $level
    ;

    protected \Stringable|string $message;

    protected ?\Throwable $exception;

    /**
     * @var array<string,mixed> $context
     */
    protected array $context = [];


    public function setData(string $time_mark, string $level, \Stringable|string $message, array $context = []): static
    {
        $this->time_mark = $time_mark;
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;

        if ($this->message instanceof \Throwable) {
            $this->context['exception'] = $this->message;
            $this->message = $this->message->getMessage();
        }
        if (array_key_exists('exception', $this->context)) {
            $this->exception = $this->context['exception'];
            unset($this->context['exception']);
        } else {
            $this->exception = null;
        }
        $this->message = (string) $this->message;
        foreach ($this->context as $key => $value) {
            if (strpos($this->message, "{" . $key . "}") !== false && is_scalar($value)) {
                $this->message = str_replace("{" . $key . "}", (string) $value, $this->message);
                unset($this->context[$key]);
            }
        }
        return $this;
    }
}