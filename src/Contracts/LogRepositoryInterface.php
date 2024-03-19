<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Contracts;

interface LogRepositoryInterface
{
    public function setComposer(LogComposerInterface $composer): self;
    public function setTimeFormat(string $timeformat): self;

    public function save(string $level, \Stringable|string $message, array $context = []): bool;
}