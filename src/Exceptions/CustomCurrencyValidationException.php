<?php

namespace PostScripton\Money\Exceptions;

use Exception;

class CustomCurrencyValidationException extends Exception
{
    public function __construct(string $value)
    {
        parent::__construct($value, 422);
    }
}
