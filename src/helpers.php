<?php

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

if (!function_exists('money')) {
    /**
     * Creates a Money object
     * @param float $amount
     * @param null $currency
     * @param null $settings
     * @return Money
     */
    function money(float $amount, $currency = null, $settings = null): Money
    {
        return new Money($amount, $currency, $settings);
    }
}

if (!function_exists('currency')) {
    /**
     * Returns currency
     * @param string $code
     * @return Currency
     * @throws CurrencyDoesNotExistException
     */
    function currency(string $code): Currency
    {
        return Currency::code($code);
    }
}

if (!function_exists('settings')) {
    /** Creates settings for Money object */
    function settings(): MoneySettings
    {
        return new MoneySettings();
    }
}