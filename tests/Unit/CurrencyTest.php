<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currencies;
use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\CurrencyHasWrongConstructorException;
use PostScripton\Money\Exceptions\NoSuchCurrencySymbolException;
use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class CurrencyTest extends TestCase
{
    use InteractsWithConfig;

    /**
     * @test
     * @dataProvider invalidConstructorsDataProvider
     */
    public function invalidConstructorThrowsAnExceptions(array $currencyData): void
    {
        $this->expectException(CurrencyHasWrongConstructorException::class);

        new Currency($currencyData);
    }

    /** @test */
    public function checkingCurrencyPropsByCodeTest(): void
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
    public function noCurrencyByISOCodeTest(): void
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currency::code('NO_SUCH_CODE');
    }

    /** @test */
    public function noCurrencyByNumCodeTest(): void
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currency::code('000');
    }

    /** @test */
    public function gettingSpecificCurrencySymbolDoesNotMatterBecauseThereIsOnlyOne(): void
    {
        $randomIndex = 1234;
        $currencyWithOneSymbol = currency('USD');

        $this->assertEquals('$', $currencyWithOneSymbol->getSymbol($randomIndex));
    }

    /** @test */
    public function gettingFirstCurrencySymbolOutOfSeveralOnesByNotSpecifyingIndex(): void
    {
        $currencyWithSeveralSymbols = currency('JPY');

        $this->assertEquals('¥', $currencyWithSeveralSymbols->getSymbol());
    }

    /** @test */
    public function gettingSpecificCurrencySymbolOutOfSeveralOnes(): void
    {
        $currencyWithSeveralSymbols = currency('JPY');
        $lastIndex = count($currencyWithSeveralSymbols->getSymbols()) - 1;

        $this->assertEquals('円', $currencyWithSeveralSymbols->getSymbol($lastIndex));
    }

    /** @test */
    public function gettingRandomCurrencySymbolOutOfSeveralOnesThrowsAnException(): void
    {
        $this->expectException(NoSuchCurrencySymbolException::class);

        $currencyWithSeveralSymbols = currency('JPY');
        $outOfBoundsIndex = count($currencyWithSeveralSymbols->getSymbols());

        $currencyWithSeveralSymbols->getSymbol($outOfBoundsIndex);
    }

    /** @test */
    public function nothingHappensOnSettingPreferredCurrencySymbolWhenThereIsOnlyOne(): void
    {
        $currencyWithOneSymbol = currency('USD');
        $randomIndex = 1234;

        $currencyWithOneSymbol->setPreferredSymbol($randomIndex);

        $this->assertEquals('$', $currencyWithOneSymbol->getSymbol());
    }

    /** @test */
    public function gettingPreferredCurrencySymbolOutOfSeveralOnes(): void
    {
        $currencyWithSeveralSymbols = currency('JPY');

        $currencyWithSeveralSymbols->setPreferredSymbol(1);

        $this->assertEquals('円', $currencyWithSeveralSymbols->getSymbol());
    }

    /** @test */
    public function gettingRandomPreferredCurrencySymbolOutOfSeveralOnesThrowsAnException(): void
    {
        $this->expectException(NoSuchCurrencySymbolException::class);

        $currencyWithSeveralSymbols = currency('JPY');
        $outOfBoundsIndex = count($currencyWithSeveralSymbols->getSymbols());

        $currencyWithSeveralSymbols->setPreferredSymbol($outOfBoundsIndex);
    }

    /** @test */
    public function resettingPreferredSymbol()
    {
        $currencyWithSeveralSymbols = currency('JPY');
        $this->assertEquals('¥', $currencyWithSeveralSymbols->getSymbol());

        $currencyWithSeveralSymbols->setPreferredSymbol(1);
        $this->assertEquals('円', $currencyWithSeveralSymbols->getSymbol());

        $currencyWithSeveralSymbols->setPreferredSymbol(null);
        $this->assertEquals('¥', $currencyWithSeveralSymbols->getSymbol());
    }

    /** @test */
    public function gettingAnArrayOfSymbols(): void
    {
        $currencyWithOneSymbol = currency('USD');
        $currencyWithSeveralSymbols = currency('JPY');

        $this->assertIsArray($currencyWithOneSymbol->getSymbols());
        $this->assertCount(1, $currencyWithOneSymbol->getSymbols());
        $this->assertEquals('$', $currencyWithOneSymbol->getSymbols()[0]);

        $this->assertIsArray($currencyWithSeveralSymbols->getSymbols());
        $this->assertCount(2, $currencyWithSeveralSymbols->getSymbols());
        $this->assertEquals('¥', $currencyWithSeveralSymbols->getSymbols()[0]);
        $this->assertEquals('円', $currencyWithSeveralSymbols->getSymbols()[1]);
    }

    // todo extract into another CurrenciesTest class
    /** @test */
    public function getAllTheCurrenciesAsArray(): void
    {
        $actual = require __DIR__ . '/../../src/Lists/popular_currencies.php';
        $allCurrencies = Currencies::getCodesArray();

        $this->assertCount(count($actual), $allCurrencies);
        $this->assertEquals(
            collect($actual)->map(fn(array $currency) => $currency['iso_code'])->toArray(),
            $allCurrencies
        );
    }

    public function testGet(): void
    {
        $this->assertEquals('RUB', Currency::get('RUB')->getCode());
        $this->assertEquals('RUB', Currency::get(currency('RUB'))->getCode());
        $this->assertNull(Currency::get(null));
    }

    public function testGetOrDefault(): void
    {
        $this->assertEquals('RUB', Currency::getOrDefault('RUB')->getCode());
        $this->assertEquals('RUB', Currency::getOrDefault(currency('RUB'))->getCode());
        $this->assertEquals('USD', Currency::getOrDefault(null)->getCode());
    }

    protected function invalidConstructorsDataProvider(): array
    {
        return [
            [
                'currencyData' => [],
            ],
            [
                'currencyData' => [
                    'name' => 'test',
                    'iso_code' => 'test',
                    'num_code' => 'test',
                    'symbol' => 'test',
                ],
            ],
            [
                'currencyData' => [
                    'full_name' => 'test',
                    'iso_code' => 'test',
                    'num_code' => 'test',
                    'symbol' => 'test',
                ],
            ],
            [
                'currencyData' => [
                    'full_name' => 'test',
                    'name' => 'test',
                    'num_code' => 'test',
                    'symbol' => 'test',
                ],
            ],
            [
                'currencyData' => [
                    'full_name' => 'test',
                    'name' => 'test',
                    'iso_code' => 'test',
                    'symbol' => 'test',
                ],
            ],
            [
                'currencyData' => [
                    'full_name' => 'test',
                    'name' => 'test',
                    'iso_code' => 'test',
                    'num_code' => 'test',
                ],
            ],
        ];
    }

    protected function tearDown(): void
    {
        $this->tearDownConfig();

        currency('USD')->setPreferredSymbol(null);
        currency('JPY')->setPreferredSymbol(null);

        parent::tearDown();
    }
}
