<?php

namespace PostScripton\Money\Exceptions;

use Exception;

class BaseException extends Exception
{
    public function __construct($message, $code = 0, BaseException $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}