<?php

declare(strict_types=1);

namespace JuanchoSL\Logger;

use JuanchoSL\Logger\Composers\PlainText;
use JuanchoSL\Logger\Contracts\LogRepositoryInterface;
use JuanchoSL\Logger\Repositories\FileRepository;
use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{

    private LogRepositoryInterface $handler;
    
    public function __construct(LogRepositoryInterface|string $path)
    {
        if (is_string($path)) {
            /*$this->full_path = $path;
            if (!is_dir($dir = pathinfo($this->full_path, PATHINFO_DIRNAME))) {
                mkdir($dir, 0666, true);
            }*/
            $this->handler = (new FileRepository($path))->setComposer(new PlainText);
            //trigger_error("Use Log data handlers instead full path for logs", E_USER_DEPRECATED);
        } else {
            $this->handler = $path;
        }
    }

    /**
     * @param array<string,mixed> $context
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->handler->save($level, $message, $context);
    }
}