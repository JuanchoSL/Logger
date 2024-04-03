<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Repositories;

use JuanchoSL\Logger\Contracts\LogComposerInterface;
use JuanchoSL\Logger\Contracts\LogRepositoryInterface;

abstract class AbstractRepository implements LogRepositoryInterface
{

    protected LogComposerInterface $composer;

    public function setComposer(LogComposerInterface $composer): static
    {
        $this->composer = $composer;
        return $this;
    }

        /**
     * @param string $level The message level
     * @param \Stringable|string $message The message
     * @param array<string,mixed> $context The context
     * @return mixed The compose result
     */
    protected function getComposed(string $level, \Stringable|string $message, array $context = []): mixed
    {
        $time = new \DateTimeImmutable('now', new \DateTimeZone(date_default_timezone_get()));
        return $this->composer->setData($time, $level, $message, $context)->compose();
    }
}