<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Contracts;

interface LogRepositoryInterface
{
    /**
     * @param LogComposerInterface $composer Message composer
     * @return static The object
     */
    public function setComposer(LogComposerInterface $composer): static;

    /**
     * @param string $level The message level
     * @param \Stringable|string $message The message
     * @param array<string,mixed> $context The context
     * @return bool The operation result
     */
    public function save(string $level, \Stringable|string $message, array $context = []): bool;
}