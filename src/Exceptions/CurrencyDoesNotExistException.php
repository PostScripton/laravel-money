<?php

namespace PostScripton\Money\Exceptions;

use Exception;

class CurrencyDoesNotExistException extends Exception
{
    public function __construct(string $code, string $list)
    {
        parent::__construct(
            'The currency code must be either alphabetical or numeric. ' .
            "The currency [{$code}] doesn't exist in the [{$list}] list."
        );
    }
}
