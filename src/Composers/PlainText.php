<?php

declare(strict_types=1);

namespace JuanchoSL\Logger\Composers;

class PlainText extends TextComposer
{

    public function compose(): mixed
    {
        trigger_error("The PlainText composer is deprecated, use TextComposer instead it", E_USER_DEPRECATED);
        return parent::compose();
    }

}