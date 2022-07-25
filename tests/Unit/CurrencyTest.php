<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\NoSuchCurrencySymbolException;
use PostScripton\Money\Tests\TestCase;

class CurrencyTest extends TestCase
{
    private string $list;

    /** @test */
    public function checkingCurrencyPropsByCodeTest()
    {
        $cur = Currency::code('RUB');
        $this->assertEquals('Russian ruble', $cur->getFullName());
        $this->assertEquals('ruble', $cur->getName());
        $this->assertEquals('RUB', $cur->getCode());
        $this->assertEquals('643', $cur->getNumCode());
        $this->assertEquals('₽', $cur->getSymbol());
        $this->assertEquals(Currency::POSITION_END, $cur->getPosition());
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
    public function currencyWithTwoSymbols()
    {
        Currency::setCurrencyList(Currency::LIST_ALL);

        $cve = Currency::code('CVE');
        $this->assertEquals('Esc', $cve->getSymbol());
        $this->assertEquals('$', $cve->getSymbol(1));
    }

    /** @test */
    public function currencyWithTwoSymbolsException()
    {
        Currency::setCurrencyList(Currency::LIST_ALL);

        $cve = Currency::code('CVE');
        $this->expectException(NoSuchCurrencySymbolException::class);
        $cve->getSymbol(1234);
    }

    /** @test */
    public function currencyNoSymbolException()
    {
        Currency::setCurrencyList(Currency::LIST_POPULAR);

        // No exception because it has only 1 symbol
        $this->assertEquals('$', Currency::code('USD')->getSymbol(1234));
    }

    /** @test */
    public function preferredSymbol()
    {
        Currency::setCurrencyList(Currency::LIST_ALL);

        $money = money('1234000', Currency::code('CVE'));
        $this->assertEquals('123.4 Esc', $money->toString());

        $this->assertEquals('Esc', $money->getCurrency()->getSymbol()); // because no preferred

        $money->getCurrency()->setPreferredSymbol(1);
        $this->assertEquals('123.4 $', $money->toString());

        $this->assertEquals('$', $money->getCurrency()->getSymbol()); // because of preferred

        $this->assertEquals('Esc', $money->getCurrency()->getSymbol(0));
        $this->assertEquals('$', $money->getCurrency()->getSymbol(1));

        Currency::setCurrencyList(Currency::LIST_CONFIG);
    }

    /** @test */
    public function getAllTheCurrenciesAsArray()
    {
        Currency::setCurrencyList(Currency::LIST_POPULAR);
        $actual = require __DIR__ . '/../../src/Lists/popular_currencies.php';
        $allCurrencies = Currency::getCurrencies();

        $this->assertCount(count($actual), $allCurrencies);
        $this->assertEquals(
            collect($actual)->map(fn(array $currency) => $currency['iso_code'])->toArray(),
            $allCurrencies
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->list = Currency::currentList();
    }

    protected function tearDown(): void
    {
        Currency::setCurrencyList($this->list);
        parent::tearDown();
    }
}
