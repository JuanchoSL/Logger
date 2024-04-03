<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Contracts;

interface LogComposerInterface
{

    /**
     * @return mixed The compose result
     */
    public function compose(): mixed;

    /**
     * @param string $timeformat A valid constant or date format
     * @return static The object
     */
    public function setTimeFormat(string $timeformat): static;

    /**
     * @param \DateTimeInterface $time_mark The datetime object for time mark
     * @param string $level The message level
     * @param \Stringable|string $message The message
     * @param array<string,mixed|\Throwable> $context The context
     * @return static The object
     */
    public function setData(\DateTimeInterface $time_mark, string $level, \Stringable|string $message, array $context = []): LogComposerInterface;

}