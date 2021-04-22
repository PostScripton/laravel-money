<?php

namespace PostScripton\Money\Exceptions;


class ShouldPublishConfigFileException extends BaseException
{
    public function __construct($code = 0, BaseException $previous = null)
    {
        parent::__construct(
            'Please publish the config file by running \'php artisan vendor:publish --tag=money\'',
            $code,
            $previous
        );
    }
}