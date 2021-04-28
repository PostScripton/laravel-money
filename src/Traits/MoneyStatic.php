<?php

namespace PostScripton\Money\Traits;

use PostScripton\Money\Currency;
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
}