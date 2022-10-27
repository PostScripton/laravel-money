<?php

namespace PostScripton\Money\Exceptions;

use Exception;

class CustomCurrencyTakenCodesException extends Exception
{
    public function __construct(string $name, string $iso, string $num)
    {
        parent::__construct(
            'Some custom currency uses the taken codes of ' .
            "the already existing currency: [{$name}] ({$iso}|{$num})"
        );
    }
}
