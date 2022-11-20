<?php

use PostScripton\Money\Currency;
use PostScripton\Money\Money;

if (! function_exists('money')) {
    /**
     * Creates a monetary object
     * @param string $amount <p>
     * Raw amount: 12345 stands for 1.2345 </p>
     * @param Currency|null $currency
     * @return Money
     */
    function money(string $amount, ?Currency $currency = null): Money
    {
        return new Money($amount, $currency);
    }
}

if (! function_exists('money_parse')) {
    /**
     * Parses the string and turns it into a monetary instance
     * @param string $money
     * @param string|null $currencyCode
     * @return Money
     */
    function money_parse(string $money, ?string $currencyCode = null): Money
    {
        return Money::parse($money, $currencyCode);
    }
}

if (! function_exists('currency')) {
    /**
     * Returns currency
     * @param string $code
     * @return Currency
     * @throws \PostScripton\Money\Exceptions\CurrencyDoesNotExistException
     */
    function currency(string $code): Currency
    {
        return Currency::code($code);
    }
}
