<?php

namespace PostScripton\Money\Exceptions;

use Exception;

class RateExchangerException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct(sprintf(
            '%s: %s',
            config('money.rate_exchanger', 'Rate Exchanger'),
            $message,
        ));
    }
}
