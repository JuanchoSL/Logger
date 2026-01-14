<?php declare(strict_types=1);

namespace JuanchoSL\Logger\Repositories;

class ScreenRepository extends AbstractRepository
{

    public function save(string $level, \Stringable|string $message, array $context = []): bool
    {
        $result = $this->getComposed($level, $message, $context);
        $data = '';
        if (is_array($result)) {
            $data .= print_r($result, true);
        } elseif (is_string($result) OR $result instanceof Stringable) {
            $data .= (string) $result;
        } elseif ($result instanceof \JsonSerializable) {
            $data .= (string) json_encode($result, JSON_PRETTY_PRINT);
        } elseif (is_object($result)) {
            ob_start();
            var_dump($result);
            $data .= ob_get_contents();
            ob_end_clean();
        }
        if (PHP_SAPI != 'cli') {
            $data = "<pre>" . $data . "</pre>";
        }
        echo $data;
        return true;
    }
}