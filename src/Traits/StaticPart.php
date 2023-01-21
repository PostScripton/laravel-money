<?php

namespace PostScripton\Money\Traits;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\Parser;

trait StaticPart
{
    public static function parse(string $money, Currency|string|null $currency = null): Money
    {
        return Parser::parse($money, $currency);
    }

    public static function of(string $amount, Currency|string|null $currency = null): Money
    {
        return money($amount, $currency);
    }

    public static function correctInput(string $input): string
    {
        if (! str_contains($input, '.')) {
            return $input;
        }

        return substr($input, 0, strpos($input, '.') + config('money.formatting.decimals') + 1);
    }

    public static function min(Money ...$list): ?Money
    {
        $collection = collect($list);

        if ($collection->isEmpty()) {
            return null;
        }

        $first = $collection->shift();

        return $collection->reduce(
            callback: fn(Money $min, Money $money) => $money->lessThan($min) ? $money : $min,
            initial: $first,
        );
    }

    public static function max(Money ...$list): ?Money
    {
        $collection = collect($list);

        if ($collection->isEmpty()) {
            return null;
        }

        $first = $collection->shift();

        return $collection->reduce(
            callback: fn(Money $max, Money $money) => $money->greaterThan($max) ? $money : $max,
            initial: $first,
        );
    }

    public static function avg(Money ...$list): ?Money
    {
        return self::sum(...$list)?->divide(count($list));
    }

    public static function sum(Money ...$list): ?Money
    {
        $collection = collect($list);

        if ($collection->isEmpty()) {
            return null;
        }

        return $collection->reduce(
            callback: fn(Money $acc, Money $cur) => $acc->add($cur),
            initial: money('0', $collection->first()->getCurrency()),
        );
    }

    public static function getDefaultDivisor(): int
    {
        return 10 ** Money::MAX_DECIMALS;
    }
}
