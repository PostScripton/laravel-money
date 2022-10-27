<?php

namespace PostScripton\Money\Exceptions;

use OutOfBoundsException;

class NoSuchCurrencySymbolException extends OutOfBoundsException
{
    public function __construct()
    {
        parent::__construct('There\'s no symbol with this index in the symbols array');
    }
}
