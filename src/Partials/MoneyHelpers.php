<?php

namespace PostScripton\Money\Partials;

use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Exceptions\NotNumericOrMoneyException;
use PostScripton\Money\Exceptions\UndefinedOriginException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

trait MoneyHelpers
{
    private function getDivisor(): int
    {
        return 10 ** $this->settings()->getDecimals();
    }

    private function numberIntoCorrectOrigin($money, int $origin = MoneySettings::ORIGIN_INT, ?string $method = null)
    {
        list($money, $origin) = $this->numberOrMoney($money, $origin, $method ?? __METHOD__);

        // If origins are not the same
        if ($this->settings()->getOrigin() !== $origin) {
            return $this->settings()->getOrigin() === MoneySettings::ORIGIN_INT
                ? floor($money * $this->getDivisor()) // $origin is float
                : $money / $this->getDivisor(); // $origin is int
        }

        return $money;
    }

    private function numberOrMoney($money, int $origin, string $method): array
    {
        if (!is_numeric($money) && !$money instanceof self) {
            throw new NotNumericOrMoneyException(__METHOD__, 1, '$money');
        }

        if ($money instanceof Money) {
           if (!$this->isSameCurrency($money)) {
                // In the future it will be converted automatically with no exceptions
                throw new MoneyHasDifferentCurrenciesException($method, 1, '$money');
            }

            $origin = $money->settings()->getOrigin();
            $money = $money->getPureAmount();
        }

        if (MoneySettings::isIncorrectOrigin($origin)) {
            throw new UndefinedOriginException($method, 2, '$origin');
        }

        return [$money, $origin];
    }
}