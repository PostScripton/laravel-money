<?php

namespace PostScripton\Money\Exceptions;

use Throwable;

class RateExchangerAPIChangedException extends RateExchangerException
{
    public function __construct(string $data, Throwable $exception)
    {
        parent::__construct(sprintf(
            'API has been changed, open a new issue on github: ' .
            'https://github.com/PostScripton/laravel-money/issues/new. ' .
            'Also send details: %s. Exception message: %s',
            $data,
            $exception->getMessage(),
        ));
    }
}
