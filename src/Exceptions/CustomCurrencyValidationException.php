<?php

namespace PostScripton\Money\Exceptions;

class CustomCurrencyValidationException extends BaseException
{
    public function __construct(string $value, $code = 422, BaseException $previous = null)
    {
        parent::__construct($value, $code, $previous);
    }
}
