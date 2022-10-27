<?php

namespace PostScripton\Money;

use Exception;
use PostScripton\Money\Rules\Money as MoneyRule;

class Parser
{
    private const MONEY_REGEX = '/^(?:(?<start_currency>%s)\s?)?(?<amount>%s)(?:\s?(?<end_currency>%s))?$/';

    public static function parse(string $money, ?string $currencyCode = null): Money
    {
        $currency = Currency::code($currencyCode ?? Currency::getConfigCurrency());
        $symbols = array_map(fn(string $symbol) => self::quote($symbol), $currency->getSymbols());
        $currencies = [...$symbols, $currency->getCode()];

        $joinedCurrencies = implode('|', $currencies);
        $pattern = sprintf(
            self::MONEY_REGEX,
            $joinedCurrencies,
            trim(MoneyRule::AMOUNT_REGEX, '/^$'),
            $joinedCurrencies
        );
        if (! preg_match($pattern, $money, $result)) {
            throw new Exception("Unable to parse [{$money}] into a monetary object");
        }

        $amount = str_replace(' ', '', $result['amount']);
        $amount = (string) ($amount * Money::getDefaultDivisor()); // todo rework it with bcmath later
        $amount = substr($amount, strpos($amount, '.'));

        return money($amount, $currency);
    }

    private static function quote(string $str): string
    {
        return trim($str) === '' ? '\s' : preg_quote($str);
    }
}
