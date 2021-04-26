<?php

use PostScripton\Money\Currency;

return [
	'RUB' => [
		'symbol' => '₽',
		'position' => Currency::POS_END,
	],
	'USD' => [
		'symbol' => '$',
		'position' => Currency::POS_START,
	],
	'EUR' => [
		'symbol' => '€',
		'position' => Currency::POS_START,
	],
];