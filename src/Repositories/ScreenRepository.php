<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Repositories;

class ScreenRepository extends AbstractRepository
{

    public function save(string $level, \Stringable|string $message, array $context = []): bool
    {
        $result = $this->getComposed($level, $message, $context);
        if (is_array($result)) {
            print_r($result);
        } elseif (is_object($result)) {
            var_dump($result);
        } elseif (is_string($result)) {
            echo $result;
        }
        return true;
    }
}