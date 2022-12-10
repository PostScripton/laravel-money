<?php

namespace PostScripton\Money\Exceptions;

class CurrenciesNotSupportedByRateExchangerException extends RateExchangerException
{
    public function __construct(array $notSupportedCodes)
    {
        parent::__construct(sprintf(
            'The rate exchanger doesn\'t support one of the currencies [%s]',
            join(', ', $notSupportedCodes),
        ));
    }
}
