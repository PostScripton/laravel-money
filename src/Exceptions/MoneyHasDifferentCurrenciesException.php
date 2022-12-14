<?php

namespace PostScripton\Money\Exceptions;

use Exception;
use PostScripton\Money\Currency;

class MoneyHasDifferentCurrenciesException extends Exception
{
    public function __construct(Currency $given, Currency $main)
    {
        parent::__construct(sprintf(
            'The given monetary object must be the same currency as the main one. Given: [%s], main: [%s]',
            $given->getCode(),
            $main->getCode(),
        ));
    }
}
