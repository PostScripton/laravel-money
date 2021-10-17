<?php

namespace PostScripton\Money\Exceptions;

class ServiceDoesNotSupportCurrencyException extends BaseException
{
    public function __construct(array $currencies, string $class, $code = 0, BaseException $previous = null)
    {
        parent::__construct(
            "The service class \"{$class}\" doesn't support one of the currencies [" .
                implode(', ', $currencies) . "].",
            $code,
            $previous
        );
    }
}
