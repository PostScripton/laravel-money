<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;

class CurrencyTest extends TestCase
{
	/** @test
	 * @throws CurrencyDoesNotExistException
	 */
	public function CheckingCurrencyPropsByCodeTest()
	{
		$cur = Currency::code('RUB');
		$this->assertEquals('₽', $cur->getSymbol());
		$this->assertEquals('RUB', $cur->getCode());
		$this->assertEquals('end', $cur->getPosition());
	}

	/** @test */
	public function NoCurrencyByCodeTest()
	{
		$this->expectException(CurrencyDoesNotExistException::class);
		Currency::code('NO_SUCH_CODE');
	}
}