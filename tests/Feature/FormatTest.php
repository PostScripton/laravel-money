<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Money;

class FormatTest extends TestCase
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
}