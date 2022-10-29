<?php

namespace PostScripton\Money\Exceptions;

use RuntimeException;

class ServiceRequestFailedException extends RuntimeException
{
    public function __construct(string $service, string $errCode, string $errMsg)
    {
        parent::__construct("{$service}: [{$errCode}] {$errMsg}");
    }
}
