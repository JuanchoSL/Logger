<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Composers;

class ArrayComposer extends AbstractComposer
{

    public function compose(): mixed
    {
        $array = [
            'time' => '',
            'level' => '',
            'message' => '',
            'code' => '',
            'file' => '',
            'line' => '',
            'trace' => [],
            'context' => [],
        ];
        $array['time'] = $this->time_mark->format($this->timeformat);
        $array['level'] = $this->level;
        $array['message'] = $this->message;
        if ($this->exception instanceof \Throwable) {
            $array['code'] = $this->exception->getCode();
            $array['file'] = $this->exception->getFile();
            $array['line'] = $this->exception->getLine();
            $array['trace'] = $this->exception->getTrace();
        }

        if (!empty ($this->context)) {
            $array['context'] = $this->context;
        }
        return $array;
    }

}