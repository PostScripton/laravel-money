<?php

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
	| When true is provided: 123()
	| When false is provided: 123(.0)
    |
    */
	'ends_with_0' => false
];