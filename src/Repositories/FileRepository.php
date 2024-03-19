<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Repositories;

class FileRepository extends AbstractRepository
{

    private string $full_path;

    public function __construct(string $full_path)
    {
        $this->full_path = $full_path;
        if (!is_dir($dir = pathinfo($this->full_path, PATHINFO_DIRNAME))) {
            mkdir($dir, 0666, true);
        }
    }

    public function save(string $level, \Stringable|string $message, array $context = []): bool
    {
        $time = date($this->timeformat) . " " . date_default_timezone_get();
        $string = $this->composer->setData($time, $level, $message, $context)->compose();

        return file_put_contents($this->full_path, $string . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
    }
}