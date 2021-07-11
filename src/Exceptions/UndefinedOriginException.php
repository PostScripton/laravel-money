<?php

namespace PostScripton\Money\Exceptions;

use PostScripton\Money\MoneySettings;

class UndefinedOriginException extends ValueErrorException
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
            'has wrong value',
            'Use ' . MoneySettings::class . '::ORIGIN_* to be sure you use correct one',
            $code,
            $previous
        );
    }
}
