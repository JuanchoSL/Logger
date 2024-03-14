<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Contracts;

interface HandlerInterface
{
    public function setTimestamp(int $timestamp): self;
    
    public function setTimeFormat(string $timeformat): self;
    
    public function setMessage(string $message): self;
        
    public function setLevel(string $level): self;

    public function setContext(array $context): self;

    public function save(): bool;
}