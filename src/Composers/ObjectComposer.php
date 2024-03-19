<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Composers;

class ObjectComposer extends AbstractComposer
{

    public function compose(): mixed
    {
        $object = new \stdClass;
        $object->time = '';
        $object->level = '';
        $object->message = '';
        $object->code = '';
        $object->file = '';
        $object->line = '';
        $object->trace = [];
        $object->context = [];

        $object->time = $this->time_mark;
        $object->level = $this->level;
        $object->message = $this->message;
        if ($this->exception instanceof \Throwable) {
            $object->code = $this->exception->getCode();
            $object->file = $this->exception->getFile();
            $object->line = $this->exception->getLine();
            $object->trace = $this->exception->getTrace();
        }

        if (!empty ($this->context)) {
            $object->context = $this->context;
        }
        return $object;
    }

}