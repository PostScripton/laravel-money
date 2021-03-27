<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Money;

class MoneyTest extends TestCase
{
	/** @test
	 * @throws CurrencyDoesNotExistException
	 */
	public function BaseOfFormatting()
	{
		$usd = Currency::code('USD');
		$rub = Currency::code('RUB');

		$this->assertEquals('$ 123', Money::format(1230, $usd));
		$this->assertEquals('$ 123.4', Money::format(1234, $usd));
		$this->assertEquals('$ 1 234', Money::format(12340, $usd));
		$this->assertEquals('$ 1 234.5', Money::format(12345, $usd));

		$this->assertEquals('123 ₽', Money::format(1230, $rub));
		$this->assertEquals('123.4 ₽', Money::format(1234, $rub));
		$this->assertEquals('1 234 ₽', Money::format(12340, $rub));
		$this->assertEquals('1 234.5 ₽', Money::format(12345, $rub));
	}

	/** @test
	 * @throws CurrencyDoesNotExistException
	 */
	public function ConvertCurrenciesTest()
	{
		$diff = 75.32;
		$rub = Money::format(10000, Currency::code('RUB'));

		$usd = Money::convert($rub, Currency::code('USD'), 1 / $diff);
		$rub = Money::convert($usd, Currency::code('RUB'), $diff / 1);

		$this->assertEquals('$ 13.2', $usd);
		$this->assertEquals('994.2 ₽', $rub);
	}
}