<?php

namespace PostScripton\Money\Exceptions;

use PostScripton\Money\Services\AbstractService;

class ServiceDoesNotInheritServiceException extends BaseException
{
    public function __construct(string $value, $code = 0, BaseException $previous = null)
    {
        parent::__construct(
            "The given service class \"{$value}\" doesn't inherit the \"" . AbstractService::class . "\".",
            $code,
            $previous
        );
    }
}
