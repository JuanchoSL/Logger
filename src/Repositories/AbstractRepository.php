<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Repositories;

use JuanchoSL\Logger\Contracts\LogComposerInterface;
use JuanchoSL\Logger\Contracts\LogRepositoryInterface;

abstract class AbstractRepository implements LogRepositoryInterface
{

    protected string $timeformat = DATE_ATOM;

    protected LogComposerInterface $composer;

    /**
     * @var array<string,mixed> $context
     */
    protected array $context = [];

    public function setComposer(LogComposerInterface $composer): self
    {
        $this->composer = $composer;
        return $this;
    }

    public function setTimeFormat(string $timeformat): self
    {
        $this->timeformat = $timeformat;
        return $this;
    }

}