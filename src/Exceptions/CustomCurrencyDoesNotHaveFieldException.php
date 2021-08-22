<?php

namespace PostScripton\Money\Exceptions;

class CustomCurrencyDoesNotHaveFieldException extends BaseException
{
    public function __construct(string $value, $code = 0, BaseException $previous = null)
    {
        parent::__construct(
            "Some of the custom currencies don't have a field \"{$value}\"",
            $code,
            $previous
        );
    }
}
