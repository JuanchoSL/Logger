<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Contracts;

interface LogComposerInterface
{

    public function compose(): mixed;
    public function setData(string $time_mark, string $level, \Stringable|string $message, array $context = []): static;
}