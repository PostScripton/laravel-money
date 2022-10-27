<?php

namespace PostScripton\Money\Exceptions;

use RuntimeException;

class ServiceRequestFailedException extends RuntimeException
{
    public function __construct(string $service, string $err_code, string $err_msg)
    {
        parent::__construct("{$service}: [{$err_code}] {$err_msg}");
    }
}
