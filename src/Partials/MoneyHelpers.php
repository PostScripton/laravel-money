<?php

namespace PostScripton\Money\Partials;

use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

trait MoneyHelpers
{
    private function getDivisor(): int
    {
        return 10 ** $this->settings()->getDecimals();
    }

    private function amountIntoOrigin(int $origin)
    {
        $amount = $this->getPureAmount();

        // If origins are not the same
        if ($this->settings()->getOrigin() !== $origin) {
            $amount = $this->settings()->getOrigin() === MoneySettings::ORIGIN_INT
                ? $amount / $this->getDivisor()
                : $amount * $this->getDivisor();
        }

        return $amount;
    }

    private function numberIntoCorrectOrigin(Money $money, ?string $method = null): float
    {
        $this->validateMoney($money, $method);

        // If origins are not the same
        if ($this->settings()->getOrigin() !== $money->settings()->getOrigin()) {
            return $this->settings()->getOrigin() === MoneySettings::ORIGIN_INT
                ? $money->getPureAmount() * $this->getDivisor()  // $origin is float
                : $money->getPureAmount() / $this->getDivisor(); // $origin is int
        }

        return $money->getPureAmount();
    }

    private function validateMoney(Money $money, string $method): void
    {
        if (!$this->isSameCurrency($money)) {
            // In the future it will be converted automatically with no exceptions
            throw new MoneyHasDifferentCurrenciesException($method, 1, '$money');
        }
    }
}
