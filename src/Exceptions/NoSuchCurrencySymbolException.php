<?php

namespace PostScripton\Money\Exceptions;

class NoSuchCurrencySymbolException extends ValueErrorException
{
    public function __construct(
        string $method,
        int $arg_num,
        string $arg_name = null,
        string $message = null,
        $code = 0,
        BaseException $previous = null
    ) {
        $message = explode(',', $message);
        parent::__construct(
            $method,
            $arg_num,
            $arg_name,
            "[{$message[0]}] is out of bounds. The range [0-{$message[1]}] is supposed",
            null,
            $code,
            $previous
        );
    }
}