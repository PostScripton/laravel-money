<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\WrongParserStringException;

class Parser
{
    public static function parse(string $money): Money
    {
        // Get list of currencies
        $list = Currency::currentList();
        if ($list !== Currency::LIST_ALL) {
            Currency::setCurrencyList(Currency::LIST_ALL);
        }
        $most_popular_currencies = [
            currency('USD'),
            currency('EUR'),
            currency('JPY'),
            currency('GBP'),
            currency('AUD'),
            currency('CAD'),
            currency('CHF'),
            currency('RUB'),
            currency('UAH'),
            currency('BYN'),
        ];
        foreach ($most_popular_currencies as $most_popular_currency) {
            foreach ($most_popular_currency->getSymbols() as $symbol) {
                $symbols[] = $symbol;
            }
            $symbols[] = $most_popular_currency->getCode();
        }
        $symbols = array_unique(array_map(fn($symbol) => self::quote($symbol), $symbols), SORT_STRING);
        $currencies = implode('|', $symbols) . '|\w{1,3}';
        Currency::setCurrencyList($list);

        // Parse
        $quoted_thousands = array_map(fn($separator) => self::quote($separator), Money::FREQUENT_THOUSAND_SEPARATORS);
        $quoted_decimals = array_map(fn($separator) => self::quote($separator), Money::FREQUENT_DECIMAL_SEPARATORS);
        $separators = implode('', array_unique([...$quoted_thousands, ...$quoted_decimals]));
        $pattern = '/(' . $currencies . ')?\s?(-?\d[\d' . $separators . ']+\d)\s?(' . $currencies . ')?/';
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
            foreach ($most_popular_currencies as $popular_currency) {
                foreach ($popular_currency->getSymbols() as $symbol) {
                    if ($symbol === $parsed_currency) {
                        $currency = $popular_currency;
                        break;
                    }
                }
                if (isset($currency)) {
                    break;
                }
                if ($popular_currency->getCode() === strtoupper($parsed_currency)) {
                    $currency = $popular_currency;
                    break;
                }
            }
        }
        if (!isset($currency)) {
            $currency = Money::getDefaultCurrency();
        }

        // Return a new money instance
        $settings = settings()
            ->setDecimals(self::getNumberOfDecimals($amount))
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = money((float)$amount, $currency, $settings);
        $money->settings()->setOrigin(Money::getDefaultOrigin());
        return $money;
    }

    public static function quote(string $str): string
    {
        return trim($str) === '' ? '\s' : preg_quote($str);
    }

    private static function getNumberOfDecimals(string $amount): int
    {
        $decimals = explode('.', $amount);
        return array_key_exists(1, $decimals)
            ? strlen($decimals[1])
            : Money::getDefaultDecimals();
    }
}
