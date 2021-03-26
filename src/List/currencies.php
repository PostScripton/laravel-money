<?php

const START = 'start';
const END = 'end';

return [
	'RUB' => [
		'symbol' => '₽',
		'position' => END,
		'countries' => ['Russian Federation'],
	],
	'USD' => [
		'symbol' => '$',
		'position' => START,
		'countries' => [
			'The United State of America',
			'Russian Federation', //...
		],
	],
	'EUR' => [
		'symbol' => '€',
		'position' => START,
		'countries' => [],
	],
];