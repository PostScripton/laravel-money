<?php

namespace PostScripton\Money\Partials;

use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Parser;

trait MoneyStatic
{
    private static int $defaultDecimals = 1;
    private static string $defaultThousandsSeparator = ' ';
    private static string $defaultDecimalSeparator = '.';
    private static bool $defaultEndsWith0 = false;
    private static bool $defaultSpaceBetween = true;
    private static Currency $defaultCurrency;

    public static function set(MoneySettings $settings): void
    {
        self::$defaultDecimals = $settings->getDecimals();
        self::$defaultThousandsSeparator = $settings->getThousandsSeparator();
        self::$defaultDecimalSeparator = $settings->getDecimalSeparator();
        self::$defaultEndsWith0 = $settings->endsWith0();
        self::$defaultSpaceBetween = $settings->hasSpaceBetween();
        self::$defaultCurrency = Currency::code(Currency::getConfigCurrency());
    }

    public static function configNotPublished(): bool
    {
        return is_null(config('money'));
    }

    public static function parse(string $money, ?string $currencyCode = null): Money
    {
        return Parser::parse($money, $currencyCode);
    }

    // ========== GETTERS ==========

    public static function getDefaultDecimals(): int
    {
        return self::$defaultDecimals;
    }

    public static function getDefaultThousandsSeparator(): string
    {
        return self::$defaultThousandsSeparator;
    }

    public static function getDefaultDecimalSeparator(): string
    {
        return self::$defaultDecimalSeparator;
    }

    public static function getDefaultEndsWith0(): bool
    {
        return self::$defaultEndsWith0;
    }

    public static function getDefaultSpaceBetween(): bool
    {
        return self::$defaultSpaceBetween;
    }

    public static function getDefaultCurrency(): Currency
    {
        return self::$defaultCurrency;
    }

    // ========== METHODS ==========

    public static function of(string $amount, $currency = null, $settings = null): Money
    {
        return money($amount, $currency, $settings);
    }

    public static function correctInput(string $input): string
    {
        if (! str_contains($input, '.')) {
            return $input;
        }

        return substr($input, 0, strpos($input, '.') + self::$defaultDecimals + 1);
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

        return money($sum->getPureAmount(), $first->getCurrency());
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

        return money($sum->getPureAmount(), $first->getCurrency());
    }

    public static function getDefaultDivisor(): int
    {
        return 10 ** 4;
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

    private static function bindMoneyWithCurrency(Money $money, Currency $currency): string
    {
        $space = $money->settings()->hasSpaceBetween() ? ' ' : '';

        // Always has a space
        $hasSpace = $currency->getPosition() === CurrencyPosition::Start && $money->isNegative()
            || $currency->getDisplay() === CurrencyDisplay::Code;
        if ($hasSpace) {
            $space = ' ';
        }

        return $currency->getPosition() === CurrencyPosition::Start
            ? $currency->getSymbol() . $space . $money->getAmount()
            : $money->getAmount() . $space . $currency->getSymbol();
    }
}
