<?php

namespace PostScripton\Money\Formatters;

use PostScripton\Money\Money;

interface MoneyFormatter
{
    /**
     * Represents a given monetary object as a string
     * @param Money $money
     * @return string
     */
    public function format(Money $money): string;
}
