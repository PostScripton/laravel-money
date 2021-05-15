<?php

namespace PostScripton\Money\Traits;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyShouldHaveSameCurrencyException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

trait MoneyStatic
{
    private static int $default_decimals = 1;
    private static string $default_thousands_separator = ' ';
    private static string $default_decimal_separator = '.';
    private static bool $default_ends_with_0 = false;
    private static bool $default_space_between = true;
    private static Currency $default_currency;
    private static int $default_origin = MoneySettings::ORIGIN_INT;

    public static function set(MoneySettings $settings): void
    {
        self::$default_decimals = $settings->getDecimals();
        self::$default_thousands_separator = $settings->getThousandsSeparator();
        self::$default_decimal_separator = $settings->getDecimalSeparator();
        self::$default_ends_with_0 = $settings->endsWith0();
        self::$default_space_between = $settings->hasSpaceBetween();
        self::$default_currency = $settings->getCurrency();
        self::$default_origin = $settings->getOrigin();
    }

    public static function configNotPublished(): bool
    {
        return is_null(config('money'));
    }

    // ========== GETTERS ==========

    public static function getDefaultDivisor(): int
    {
        return 10 ** self::$default_decimals;
    }

    public static function getDefaultDecimals(): int
    {
        return self::$default_decimals;
    }

    public static function getDefaultThousandsSeparator(): string
    {
        return self::$default_thousands_separator;
    }

    public static function getDefaultDecimalSeparator(): string
    {
        return self::$default_decimal_separator;
    }

    public static function getDefaultEndsWith0(): bool
    {
        return self::$default_ends_with_0;
    }

    public static function getDefaultSpaceBetween(): bool
    {
        return self::$default_space_between;
    }

    public static function getDefaultCurrency(): Currency
    {
        return self::$default_currency;
    }

    public static function getDefaultOrigin(): int
    {
        return self::$default_origin;
    }

    // ========== METHODS ==========

    public static function make(float $number, $currency = null, $settings = null): Money
    {
        return new Money($number, $currency, $settings);
    }

    public static function correctInput(string $input): string
    {
        if (!str_contains($input, '.')) {
            return $input;
        }

        return substr($input, 0, strpos($input, '.') + self::$default_decimals + 1);
    }

    public static function min(Money ...$monies): ?Money
    {
        if (empty($monies)) {
            return null;
        }

        self::currenciesAreNotSame($monies, Money::class . '::' . __FUNCTION__, 1, '$monies');

        $min = $monies[0];

        for ($i = 1; $i < count($monies); $i++) {
            if (($money = $monies[$i])->getPureNumber() < $min->getPureNumber()) {
                $min = $money;
            }
        }

        return $min;
    }

    public static function max(Money ...$monies): ?Money
    {
        if (empty($monies)) {
            return null;
        }

        self::currenciesAreNotSame($monies, Money::class . '::' . __FUNCTION__, 1, '$monies');

        $max = $monies[0];

        for ($i = 1; $i < count($monies); $i++) {
            if (($money = $monies[$i])->getPureNumber() > $max->getPureNumber()) {
                $max = $money;
            }
        }

        return $max;
    }

    public static function avg(Money ...$monies): ?Money
    {
        if (empty($monies)) {
            return null;
        }

        self::currenciesAreNotSame($monies, Money::class . '::' . __FUNCTION__, 1, '$monies');

        $sum = array_reduce($monies, function (float $acc, Money $money) {
            return $acc + $money->getPureNumber();
        }, 0);

        return new Money($sum / count($monies),
            Currency::code($monies[0]->getCurrency()->getCode()),
            clone $monies[0]->settings());
    }

    public static function sum(Money ...$monies): ?Money
    {
        if (empty($monies)) {
            return null;
        }

        self::currenciesAreNotSame($monies, Money::class . '::' . __FUNCTION__, 1, '$monies');

        $sum = array_reduce($monies, function (float $acc, Money $money) {
            return $acc + $money->getPureNumber();
        }, 0);

        return new Money($sum,
            Currency::code($monies[0]->getCurrency()->getCode()),
            clone $monies[0]->settings());
    }

    private static function currenciesAreNotSame(array $monies, string $method, int $arg_num, string $arg_name): void
    {
        $main = $monies[0];

        foreach ($monies as $money) {
            if (!$main->isSameCurrency($money)) {
                throw new MoneyShouldHaveSameCurrencyException($method, $arg_num, $arg_name);
            }
        }
    }

    private static function bindMoneyWithCurrency(Money $money, Currency $currency): string
    {
        $space = $money->settings()->hasSpaceBetween() ? ' ' : '';

        // Always has a space
        if ($currency->getPosition() === Currency::POS_START && $money->isNegative() || $currency->getDisplay() === Currency::DISPLAY_CODE) {
            $space = ' ';
        }

        return $currency->getPosition() === Currency::POS_START
            ? $currency->getSymbol() . $space . $money->getNumber()
            : $money->getNumber() . $space . $currency->getSymbol();
    }
}