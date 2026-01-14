<?php declare(strict_types=1);

namespace JuanchoSL\Logger\Repositories;

use Psr\Http\Message\StreamInterface;
use Stringable;

class StreamRepository extends ScreenRepository
{

    protected StreamInterface $stream;

    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    public function save(string $level, Stringable|string $message, array $context = []): bool
    {
        ob_start();
        parent::save($level, $message, $context);
        $data = ob_get_contents();
        ob_end_clean();
        return $this->stream->write($data) > 0;
    }
}