<?php

namespace PostScripton\Money\Exceptions;

class CurrencyListConfigException extends BaseException
{
    public function __construct(string $value, $code = 0, BaseException $previous = null)
    {
        parent::__construct(
            "The config value \"money.currency_list\" must be \"all\", \"popular\", \"custom\" or an array of codes. The value \"{$value}\" was given.",
            $code,
            $previous
        );
    }
}