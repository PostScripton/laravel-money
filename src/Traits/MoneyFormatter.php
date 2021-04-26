<?php

namespace PostScripton\Money\Traits;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;

trait MoneyFormatter
{
    public static function make(float $number, $currency = null, $settings = null): Money
    {
        return new Money($number, $currency, $settings);
    }

    public static function convertOffline(Money $money, Currency $into, float $coeff): Money
    {
        $new_amount = $money->getPureNumber() * $coeff;
        $settings = clone $money->settings;

        return self::make($new_amount, $into, $settings->setCurrency($into));
    }

    public static function purify(Money $money): string
    {
        return $money->getNumber();
    }

    public static function integer(Money $money): int
    {
        return floor($money->getPureNumber());
    }

    public static function correctInput(string $input): string
    {
        if (!str_contains($input, '.')) {
            return $input;
        }

        return substr($input, 0, strpos($input, '.') + self::$default_decimals + 1);
    }

    private static function bindMoneyWithCurrency(string $money, Currency $currency, bool $space = true): string
    {
        $space = $space ? ' ' : '';
        return $currency->getPosition() === Currency::POS_START
            ? $currency->getSymbol() . $space . $money
            : $money . $space . $currency->getSymbol();
    }
}