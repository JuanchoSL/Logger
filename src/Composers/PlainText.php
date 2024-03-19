<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Composers;

class PlainText extends AbstractComposer
{

    public function compose(): mixed
    {
        if ($this->exception instanceof \Throwable) {
            $message = $this->exception->getCode() . ": " . $this->message . PHP_EOL;
            $message .= "Origin: " . $this->exception->getFile() . " (" . $this->exception->getLine() . ")" . PHP_EOL;
            $message .= $this->exception->getTraceAsString();
        } else {
            $message = $this->message;
        }

        $string = "[" . $this->time_mark . "] [" . $this->level . "] " . $message;
        if (!empty ($this->context)) {
            $string .= PHP_EOL . json_encode($this->context, JSON_PRETTY_PRINT);
        }
        return $string;
    }

}