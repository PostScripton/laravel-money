<?php

namespace PostScripton\Money\Exceptions;

class ServiceDoesNotHaveClassException extends BaseException
{
    public function __construct(string $value, $code = 0, BaseException $previous = null)
    {
        parent::__construct(
            "The service \"{$value}\" doesn't have the \"class\" property.",
            $code,
            $previous
        );
    }
}
