<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

use JuanchoSL\Logger\Contracts\LogRepositoryInterface;
use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{

    private LogRepositoryInterface $handler;
    
    public function __construct(LogRepositoryInterface $path)
    {
        $this->handler = $path;
    }

    /**
     * @param string $level
     * @param \Stringable|string $message
     * @param array<string,mixed> $context
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->handler->save($level, $message, $context);
    }
}