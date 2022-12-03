<?php

namespace PostScripton\Money\Traits;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Parser;

trait StaticPart
{
    private static Currency $defaultCurrency;

    public static function setDefaultCurrency(Currency $currency): void
    {
        self::$defaultCurrency = $currency;
    }

    public static function parse(string $money, Currency|string|null $currency = null): Money
    {
        return Parser::parse($money, $currencyCode);
    }

    public static function getDefaultCurrency(): Currency
    {
        return self::$defaultCurrency;
    }

    public static function of(string $amount, ?Currency $currency = null): Money
    {
        return money($amount, $currency);
    }

    public static function correctInput(string $input): string
    {
        if (! str_contains($input, '.')) {
            return $input;
        }

        return substr($input, 0, strpos($input, '.') + config('money.decimals') + 1);
    }

    public static function min(Money ...$list): ?Money
    {
        if (empty($list)) {
            return null;
        }

        self::checkAllCurrenciesAreTheSame($list);

        $min = $list[0];

        for ($i = 1; $i < count($list); $i++) {
            $money = $list[$i];
            if ($money->lessThan($min)) {
                $min = $money;
            }
        }

        return $min;
    }

    public static function max(Money ...$list): ?Money
    {
        if (empty($list)) {
            return null;
        }

        self::checkAllCurrenciesAreTheSame($list);

        $max = $list[0];

        for ($i = 1; $i < count($list); $i++) {
            $money = $list[$i];
            if ($money->greaterThan($max)) {
                $max = $money;
            }
        }

        return $max;
    }

    public static function avg(Money ...$list): ?Money
    {
        if (empty($list)) {
            return null;
        }

        self::checkAllCurrenciesAreTheSame($list);

        $first = $list[0];
        $sum = array_reduce(
            array: $list,
            callback: fn(Money $acc, Money $money) => $acc->add($money),
            initial: money('0', $first->getCurrency()),
        );
        $sum->divide(count($list));

        return money($sum->getAmount(), $first->getCurrency());
    }

    public static function sum(Money ...$list): ?Money
    {
        if (empty($list)) {
            return null;
        }

        self::checkAllCurrenciesAreTheSame($list);

        $first = $list[0];
        $sum = array_reduce(
            array: $list,
            callback: fn(Money $acc, Money $money) => $acc->add($money),
            initial: money('0', $first->getCurrency()),
        );

        return money($sum->getAmount(), $first->getCurrency());
    }

    public static function getDefaultDivisor(): int
    {
        return 10 ** Money::MAX_DECIMALS;
    }

    private static function checkAllCurrenciesAreTheSame(array $list): void
    {
        $main = $list[0];

        foreach ($list as $money) {
            if (! $main->isSameCurrency($money)) {
                throw new MoneyHasDifferentCurrenciesException();
            }
        }
    }
}
