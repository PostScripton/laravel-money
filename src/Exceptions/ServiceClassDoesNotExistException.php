<?php

namespace PostScripton\Money\Exceptions;

class ServiceClassDoesNotExistException extends BaseException
{
    public function __construct(string $value, $code = 0, BaseException $previous = null)
    {
        parent::__construct(
            "The service class \"{$value}\" doesn't exist.",
            $code,
            $previous
        );
    }
}
