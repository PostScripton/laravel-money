<?php

namespace PostScripton\Money\Partials;

use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

trait MoneyHelpers
{
    private function validateMoney(Money $money, string $method): void
    {
        if (!$this->isSameCurrency($money)) {
            // In the future it will be converted automatically with no exceptions
            throw new MoneyHasDifferentCurrenciesException($method, 1, '$money');
        }
    }
}
