<?php

namespace PostScripton\Money\Exceptions;

use PostScripton\Money\Money;

class NotNumericOrMoneyException extends ValueErrorException
{
    public function __construct(
        string $method,
        int $arg_num,
        string $arg_name = null,
        $code = 0,
        BaseException $previous = null
    ) {
        parent::__construct(
            $method,
            $arg_num,
            $arg_name,
            'must be numeric or instance of ' . Money::class,
            null,
            $code,
            $previous
        );
    }
}
