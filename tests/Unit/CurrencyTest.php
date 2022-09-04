<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currencies;
use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Tests\TestCase;

class CurrencyTest extends TestCase
{
    /** @test */
    public function checkingCurrencyPropsByCodeTest()
    {
        $cur = Currency::code('RUB');
        $this->assertEquals('Russian ruble', $cur->getFullName());
        $this->assertEquals('ruble', $cur->getName());
        $this->assertEquals('RUB', $cur->getCode());
        $this->assertEquals('643', $cur->getNumCode());
        $this->assertEquals('₽', $cur->getSymbol());
        $this->assertEquals(CurrencyPosition::End, $cur->getPosition());
    }

    /** @test */
    public function noCurrencyByISOCodeTest()
    {
        $this->expectException(CurrencyDoesNotExistException::class);
        Currency::code('NO_SUCH_CODE');
    }

    /** @test */
    public function noCurrencyByNumCodeTest()
    {
        $this->expectException(CurrencyDoesNotExistException::class);
        Currency::code('000');
    }

    /** @test */
    public function currencyNoSymbolException()
    {
        // No exception because it has only 1 symbol
        $this->assertEquals('$', Currency::code('USD')->getSymbol(1234));
    }

    /** @test */
    public function getAllTheCurrenciesAsArray()
    {
        $actual = require __DIR__ . '/../../src/Lists/popular_currencies.php';
        $allCurrencies = Currencies::getCodesArray();

        $this->assertCount(count($actual), $allCurrencies);
        $this->assertEquals(
            collect($actual)->map(fn(array $currency) => $currency['iso_code'])->toArray(),
            $allCurrencies
        );
    }
}
