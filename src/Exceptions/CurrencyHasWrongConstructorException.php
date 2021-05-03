<?php

namespace PostScripton\Money\Exceptions;

use PostScripton\Money\Currency;

class CurrencyHasWrongConstructorException extends BaseException
{
    public function __construct($code = 0, BaseException $previous = null)
    {
        parent::__construct(
            Currency::class . ' got wrong array as a parameter for constructor thus it can not be instantiated.',
            $code,
            $previous
        );
    }
}