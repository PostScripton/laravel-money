<?php

namespace PostScripton\Money\Exceptions;

class MoneyHasDifferentCurrenciesException extends ValueErrorException
{
    public function __construct(
        string $method,
        int $arg_num,
        string $arg_name = null,
        string $message = null,
        $code = 0,
        BaseException $previous = null
    ) {
        parent::__construct(
            $method,
            $arg_num,
            $arg_name,
            "must be the same currency as the main money",
            $message,
            $code,
            $previous
        );
    }
}
