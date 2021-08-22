<?php

namespace PostScripton\Money\Exceptions;

use PostScripton\Money\Money;

class WrongParserStringException extends ValueErrorException
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
            'is wrong. Unable to parse this string into a ' . Money::class . ' object',
            $message,
            $code,
            $previous
        );
    }
}
