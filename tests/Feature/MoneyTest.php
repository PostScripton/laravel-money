<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Money;

class MoneyTest extends TestCase
{
	/** @test */
	public function Creating()
	{
        $money1 = Money::make(12345);
        $money2 = new Money(12345);

        $this->assertEquals($money1, $money2);
	}

    /** @test
	 * @throws CurrencyDoesNotExistException
	 */
	public function BaseOfFormatting()
	{
		$usd = Currency::code('USD');
		$rub = Currency::code('RUB');

		$this->assertEquals('$ 123', Money::make(1230, $usd));
		$this->assertEquals('$ 123.4', Money::make(1234, $usd));
		$this->assertEquals('$ 1 234', Money::make(12340, $usd));
		$this->assertEquals('$ 1 234.5', Money::make(12345, $usd));

		$this->assertEquals('123 ₽', Money::make(1230, $rub));
		$this->assertEquals('123.4 ₽', Money::make(1234, $rub));
		$this->assertEquals('1 234 ₽', Money::make(12340, $rub));
		$this->assertEquals('1 234.5 ₽', Money::make(12345, $rub));
	}

	/** @test */
	public function String()
	{
	    $money = Money::make(1234);

	    $this->assertEquals('$ 123.4', $money->toString());
	    $this->assertEquals('$ 123.4', strval($money));
	    $this->assertEquals('$ 123.4', '' . $money);
	    $this->assertEquals('$ 123.4', $money);
	}
}