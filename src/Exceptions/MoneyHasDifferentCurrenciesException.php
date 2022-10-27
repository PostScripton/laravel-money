<?php

namespace PostScripton\Money\Exceptions;

use Exception;

class MoneyHasDifferentCurrenciesException extends Exception
{
    public function __construct()
    {
        parent::__construct('The given monetary object must be the same currency as the main money');
    }
}
