<?php

namespace PostScripton\Money\Exceptions;

class NoCurrencyInParserStringException extends ValueErrorException
{
    public function __construct(
        string $method,
        int $arg_num,
        string $arg_name = null,
        string $message = null,
        $code = 0,
        BaseException $previous = null
    ) {
        parent::__construct(
            $method,
            $arg_num,
            $arg_name,
            'has no or wrong currency. Ways to specify a currency: symbol ($), ISO code (USD), numeric code (840)',
            $message,
            $code,
            $previous
        );
    }
}
