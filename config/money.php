<?php

use PostScripton\Money\Enums\CurrencyList;

return [
    /*
    |--------------------------------------------------------------------------
    | Default currency
    |--------------------------------------------------------------------------
    |
    | This option controls the default currency of the package when user
    | doesn't provide any currency in methods. The default one will be
    | chosen from this option.
    | The code of the currency must be provided: USD, EUR, RUB, ...
    |
    */
    'default_currency' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Currency Lists
    |--------------------------------------------------------------------------
    |
    | This option controls which list of currencies will be used.
    |
    | For now following lists are provided:
    | 1. CurrencyList::All - all the currencies in the world.
    | 2. CurrencyList::Popular - only the most popular ones (35) are used. (default)
    | 3. CurrencyList::Custom - only custom currencies
    | 4. ['840', 'EUR', 'RUB'] - array of currency codes you need. Selects from
    |                            lists both "all" and "custom_currencies" below
    |
    | Segregation of currencies is assumed for performance purposes so that
    | unnecessary ones won't be used.
    |
    | Any list contains currencies from "custom_currencies" setting.
    |
    */
    'currency_list' => CurrencyList::Popular,

    /*
    |--------------------------------------------------------------------------
    | Custom currencies
    |--------------------------------------------------------------------------
    |
    | This option allows you to create you own currencies.
    |
    | Each custom currency represents an array with properties:
    | 1. full_name  - a full qualified name of a currency
    | 2. name       - a short name of a currency
    | 3. iso_code   - an alphabetic code
    | 4. num_code   - a numeric code
    | 5. symbol     - a symbol of a currency.
    |               If there are more than one, array of strings is passed.
    |               "$" or ["$", "$$", "$$$"]
    | 6. position   - a position of a currency either in the beginning or in the end (CurrencyPosition enum)
    |
    | Example of the existing currency:
    | [
    |   'full_name' => 'United States dollar',
    |   'name' => 'dollar',
    |   'iso_code' => 'USD',
    |   'num_code' => '840',
    |   'symbol' => '$',
    |   'position' => CurrencyPosition::Start,
    | ]
    |
    | A custom currency may override a currency from the "currency_list"
    | by specifying either the same iso or num code.
    |
    */
    'custom_currencies' => [],

    /*
    |--------------------------------------------------------------------------
    | Default rate exchanger
    |--------------------------------------------------------------------------
    |
    | This option controls the default API provider for converting currencies.
    |
    | Supported providers: "fixer", "openexchangerates" and "exchangerate"
    |
    | exchangerate (default)
    |
    */
    'rate_exchanger' => 'exchangerate',

    /*
    |--------------------------------------------------------------------------
    | Rate exchangers
    |--------------------------------------------------------------------------
    |
    | This option contains all the API providers for converting currencies.
    |
    */
    'rate_exchangers' => [

        'fixer' => [
            // https://fixer.io/
            'class' => \PostScripton\Money\Clients\RateExchangers\Fixer::class,
            'key' => env('FIXER_API_KEY'),
            'free_plan' => env('FIXER_FREE_PLAN', true),
        ],

        'openexchangerates' => [
            // https://openexchangerates.org/
            'class' => \PostScripton\Money\Clients\RateExchangers\OpenExchangeRates::class,
            'key' => env('OPENEXCHANGERATES_API_KEY'),
        ],

        'exchangerate' => [
            // https://exchangerate.host/
            'class' => \PostScripton\Money\Clients\RateExchangers\ExchangeRate::class,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Caching exchange rate results
    |--------------------------------------------------------------------------
    |
    | This option allows you to cache the API results of rate exchangers.
    |
    | It is highly recommended not to disable caching
    | because it may lead to exceeding quotas of your API accounts.
    |
    */
    'cache' => [
        'enabled' => env('MONEY_CACHE_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Cache store
        |--------------------------------------------------------------------------
        |
        | This option specifies which cache store driver to use.
        |
        | You can specify any of the `store` drivers listed in the cache.php config file.
        | Using `default` means to use the `default` driver in the cache.php.
        |
        */
        'store' => 'default',

        'rate_exchanger' => [
            // Request to get all supported currencies by rate exchanger.
            'supports' => [
                'ttl' => \DateInterval::createFromDateString('7 days'), // or `null` to store forever
            ],

            // Request to get a real-time exchange rates. Historical rates are stored forever.
            'rate' => [
                'ttl' => \DateInterval::createFromDateString('1 hour'), // or `null` to store forever
            ],
        ],
    ],

    'formatting' => [
        /*
        |--------------------------------------------------------------------------
        | Thousands separator
        |--------------------------------------------------------------------------
        |
        | This option controls the default separator between thousands
        | like 1( )000( )000
        |
        */
        'thousands_separator' => ' ',

        /*
        |--------------------------------------------------------------------------
        | Decimal separator
        |--------------------------------------------------------------------------
        |
        | This option controls the default separator between decimals
        | like 123(.)4
        |
        */
        'decimal_separator' => '.',

        /*
        |--------------------------------------------------------------------------
        | Number of digits after decimal
        |--------------------------------------------------------------------------
        |
        | This option controls the default number of digits after
        | the decimal separator like 123.(4)
        |
        */
        'decimals' => 1,

        /*
        |--------------------------------------------------------------------------
        | Ends with 0
        |--------------------------------------------------------------------------
        |
        | This option controls whether a money string ends with 0 or not.
        | When true is provided: 123(.0)
        | When false is provided: 123()
        |
        */
        'ends_with_0' => false,

        /*
        |--------------------------------------------------------------------------
        | Space between currency and number
        |--------------------------------------------------------------------------
        |
        | This option controls whether there is a space between currency symbol and number
        | When true is provided: $( )100
        | When false is provided: $()100
        |
        */
        'space_between' => true,
    ],
];
