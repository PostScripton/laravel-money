<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\WrongParserStringException;

class Parser
{
    public static function parse(string $money): Money
    {
        $currencies = Currency::getCurrencies();
        foreach ($currencies as $foundCurrency) {
            foreach ($foundCurrency->getSymbols() as $symbol) {
                $symbols[] = $symbol;
            }
            $symbols[] = $foundCurrency->getCode();
        }
        $symbols = array_unique(array_map(fn($symbol) => self::quote($symbol), $symbols), SORT_STRING);
        $quotedCurrencies = implode('|', $symbols) . '|\w{1,3}';

        // Parse
        $quoted_thousands = array_map(fn($separator) => self::quote($separator), Money::FREQUENT_THOUSAND_SEPARATORS);
        $quoted_decimals = array_map(fn($separator) => self::quote($separator), Money::FREQUENT_DECIMAL_SEPARATORS);
        $separators = implode('', array_unique([...$quoted_thousands, ...$quoted_decimals]));
        $pattern = '/(' . $quotedCurrencies . ')?\s?(-?\d[\d' . $separators . ']+\d)\s?(' . $quotedCurrencies . ')?/';
        preg_match($pattern, $money, $result);

        // If nothing is found
        if (empty($result[0])) {
            throw new WrongParserStringException(Money::class . '::' . __FUNCTION__, 1, '$money');
        }

        // Casting to PHP float
        $amount = $result[2];
        $amount = preg_replace('/([' . implode('', $quoted_thousands) . '])(?!\d{1,2}$)/', '', $amount);
        $amount = preg_replace('/[' . implode('', $quoted_decimals) . ']/', '.', $amount);

        // Find out the currency
        $parsed_currency = $result[3] ?? $result[1];
        if (!empty($parsed_currency)) {
            foreach ($currencies as $currency) {
                foreach ($currency->getSymbols() as $symbol) {
                    if ($symbol === $parsed_currency) {
                        $foundCurrency = $currency;
                        break;
                    }
                }
                if (isset($foundCurrency)) {
                    break;
                }
                if ($currency->getCode() === strtoupper($parsed_currency)) {
                    $foundCurrency = $currency;
                    break;
                }
            }
        }
        if (!isset($foundCurrency)) {
            $foundCurrency = Money::getDefaultCurrency();
        }

        return money((float)($amount * Money::getDefaultDivisor()), $foundCurrency);
    }

    public static function quote(string $str): string
    {
        return trim($str) === '' ? '\s' : preg_quote($str);
    }
}
