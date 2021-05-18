<?php

namespace PostScripton\Money\Exceptions;

class MoneyShouldHaveSameCurrencyException extends ValueErrorException
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
            "should have the same currency in order to work correctly",
            $message,
            $code,
            $previous
        );
    }
}