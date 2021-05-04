<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\NoSuchCurrencySymbolException;

class CurrencyTest extends TestCase
{
	/** @test */
	public function CheckingCurrencyPropsByCodeTest()
	{
		$cur = Currency::code('RUB');
		$this->assertEquals('Russian ruble', $cur->getFullName());
		$this->assertEquals('ruble', $cur->getName());
		$this->assertEquals('RUB', $cur->getCode());
		$this->assertEquals('643', $cur->getNumCode());
		$this->assertEquals('₽', $cur->getSymbol());
		$this->assertEquals(Currency::POS_END, $cur->getPosition());
	}

	/** @test */
	public function NoCurrencyByISOCodeTest()
	{
		$this->expectException(CurrencyDoesNotExistException::class);
		Currency::code('NO_SUCH_CODE');
	}

    /** @test */
    public function NoCurrencyByNumCodeTest()
    {
        $this->expectException(CurrencyDoesNotExistException::class);
        Currency::code('000');
    }

    /** @test */
    public function CurrencyWithTwoSymbols()
    {
        Currency::setCurrencyList(Currency::LIST_ALL);

        $cve = Currency::code('CVE');
        $this->assertEquals('Esc', $cve->getSymbol());
        $this->assertEquals('$', $cve->getSymbol(1));
    }

    /** @test */
    public function CurrencyWithTwoSymbolsException()
    {
        Currency::setCurrencyList(Currency::LIST_ALL);

        $cve = Currency::code('CVE');
        $this->expectException(NoSuchCurrencySymbolException::class);
        $cve->getSymbol(1234);
    }

    /** @test */
    public function CurrencyNoSymbolException()
    {
        Currency::setCurrencyList(Currency::LIST_POPULAR);

        // No exception because it has only 1 symbol
        $this->assertEquals('$', Currency::code('USD')->getSymbol(1234));
    }
}