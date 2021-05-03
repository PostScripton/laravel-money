<?php

use PostScripton\Money\MoneySettings;

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
    | 1. all - all the currencies in the world.
    | 2. popular - only the most popular ones (35) are used. (default)
    |
    | Segregation of currencies is assumed for performance purposes so that
    | unnecessary ones won't be used.
    |
    */
    'currency_list' => 'popular',

	/*
    |--------------------------------------------------------------------------
    | Thousands separator
    |--------------------------------------------------------------------------
    |
    | This option controls the default separator between thousands
	| like 1( )000( )000.
    |
    */
	'thousands_separator' => ' ',

	/*
    |--------------------------------------------------------------------------
    | Decimal separator
    |--------------------------------------------------------------------------
    |
    | This option controls the default separator between decimals
	| like 123(.)4.
    |
    */
	'decimal_separator'	=> '.',

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

    /*
    |--------------------------------------------------------------------------
    | Origin number from database
    |--------------------------------------------------------------------------
    |
    | This option controls the default origin for any number that will be passed
    | for creating a Money object.
    | It is used, for instance, if all of your money numbers from database are
    | represented as integer or float.
    |
    | For integer you would save money like: 1234, and would like to get "$ 123.4"
    | For float you would save money line: 123.4, and you would get "$ 123.4"
    |
    | Now only two values are provided:
    | MoneySettings::ORIGIN_INT
    | MoneySettings::ORIGIN_FLOAT
    |
    */
    'origin' => MoneySettings::ORIGIN_INT
];