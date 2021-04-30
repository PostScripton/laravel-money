<?php

namespace PostScripton\Money\Exceptions;

class NotNumericException extends ValueErrorException
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
            'must be numeric',
            null,
            $code,
            $previous
        );
    }
}