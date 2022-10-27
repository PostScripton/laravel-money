<?php

namespace PostScripton\Money\Exceptions;

use Exception;
use PostScripton\Money\Currency;

class CurrencyHasWrongConstructorException extends Exception
{
    public function __construct()
    {
        parent::__construct(sprintf(
            '%s got wrong array as a parameter for constructor thus it can not be instantiated.',
            Currency::class,
        ));
    }
}
