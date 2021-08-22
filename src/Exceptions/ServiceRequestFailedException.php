<?php

namespace PostScripton\Money\Exceptions;

class ServiceRequestFailedException extends BaseException
{
    public function __construct(
        string $service,
        string $err_code,
        string $err_msg,
        $code = 0,
        BaseException $previous = null
    ) {
        parent::__construct(
            "{$service}: [{$err_code}] {$err_msg}",
            $code,
            $previous
        );
    }
}
