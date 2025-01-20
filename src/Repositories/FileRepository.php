<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Repositories;
use JuanchoSL\Exceptions\NotModifiedException;

class FileRepository extends AbstractRepository
{

    private string $full_path;

    public function __construct(string $full_path)
    {
        $this->full_path = $full_path;
        $dir_path = pathinfo($this->full_path, PATHINFO_DIRNAME);
        if (!file_exists($dir_path)) {
            if (!mkdir($dir_path, 0766, true)) {
                throw new NotModifiedException("The directory '{$dir_path}' can not be created");
            }
        }
    }

    public function save(string $level, \Stringable|string $message, array $context = []): bool
    {
        $result = $this->getComposed($level, $message, $context);
        return file_put_contents($this->full_path, $result . PHP_EOL, FILE_APPEND) !== false;
    }
}