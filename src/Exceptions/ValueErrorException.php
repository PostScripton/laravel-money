<?php

namespace PostScripton\Money\Exceptions;

class ValueErrorException extends BaseException
{
    public function __construct(
        string $method,
        int $arg_num,
        string $arg_name = null,
        string $err_msg = 'has wrong value',
        string $message = null,
        $code = 0,
        BaseException $previous = null
    ) {
        $error = "ValueError: {$method}(): Argument #{$arg_num} ";
        $error .= is_null($arg_name) ? '' : "({$arg_name}) ";
        $error .= "{$err_msg}.";
        $error .= is_null($message) ? '' : " {$message}.";

        parent::__construct($error, $code, $previous);
    }
}