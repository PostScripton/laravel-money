<?php

namespace PostScripton\Money\Traits;

use Illuminate\Support\Collection;
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

    public static function zero(Currency|string|null $currency = null): Money
    {
        return static::of('0', $currency);
    }

    public static function min(Money|Collection|null $first = null, Money ...$list): ?Money
    {
        $collection = self::getMoneyCollection($first, ...$list);

        if ($collection->isEmpty()) {
            return null;
        }

        $first = $collection->shift();

        return $collection->reduce(
            callback: fn(Money $min, Money $money) => $money->lessThan($min) ? $money : $min,
            initial: $first,
        );
    }

    public static function max(Money|Collection|null $first = null, Money ...$list): ?Money
    {
        $collection = self::getMoneyCollection($first, ...$list);

        if ($collection->isEmpty()) {
            return null;
        }

        $first = $collection->shift();

        return $collection->reduce(
            callback: fn(Money $max, Money $money) => $money->greaterThan($max) ? $money : $max,
            initial: $first,
        );
    }

    public static function avg(Money|Collection|null $first = null, Money ...$list): ?Money
    {
        $collection = self::getMoneyCollection($first, ...$list);

        return self::sum($collection)?->divide($collection->count());
    }

    public static function sum(Money|Collection|null $first = null, Money ...$list): ?Money
    {
        $collection = self::getMoneyCollection($first, ...$list);

        if ($collection->isEmpty()) {
            return null;
        }

        $first = $collection->shift();

        return $collection->reduce(
            callback: fn(Money $sum, Money $money) => $sum->add($money),
            initial: $first,
        );
    }

    public static function getDefaultDivisor(): int
    {
        return 10 ** Money::MAX_DECIMALS;
    }

    private static function getMoneyCollection(Money|Collection|null $first = null, Money ...$list): Collection
    {
        $collection = $first instanceof Collection
            ? $first->toBase()->push(...$list)
            : collect($list)->prepend($first);

        return $collection->filter();
    }
}
