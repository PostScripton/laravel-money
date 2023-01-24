<?php

namespace PostScripton\Money\Tests\Unit;

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

    /** @dataProvider invalidConstructorsDataProvider */
    public function testInvalidConstructorThrowsAnExceptions(array $currencyData): void
    {
        $this->expectException(CurrencyHasWrongConstructorException::class);

        new Currency($currencyData);
    }

    public function testCheckingCurrencyPropsByCodeTest(): void
    {
        $cur = Currency::code('RUB');

        $this->assertEquals('Russian ruble', $cur->getFullName());
        $this->assertEquals('ruble', $cur->getName());
        $this->assertEquals('RUB', $cur->getCode());
        $this->assertEquals('643', $cur->getNumCode());
        $this->assertEquals('₽', $cur->getSymbol());
        $this->assertEquals(CurrencyPosition::End, $cur->getPosition());
    }

    public function testNoCurrencyByISOCodeTest(): void
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currency::code('NO_SUCH_CODE');
    }

    public function testNoCurrencyByNumCodeTest(): void
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currency::code('000');
    }

    public function testGettingSpecificCurrencySymbolDoesNotMatterBecauseThereIsOnlyOne(): void
    {
        $randomIndex = 1234;
        $currencyWithOneSymbol = currency('USD');

        $this->assertEquals('$', $currencyWithOneSymbol->getSymbol($randomIndex));
    }

    public function testGettingFirstCurrencySymbolOutOfSeveralOnesByNotSpecifyingIndex(): void
    {
        $currencyWithSeveralSymbols = currency('JPY');

        $this->assertEquals('¥', $currencyWithSeveralSymbols->getSymbol());
    }

    public function testGettingSpecificCurrencySymbolOutOfSeveralOnes(): void
    {
        $currencyWithSeveralSymbols = currency('JPY');
        $lastIndex = count($currencyWithSeveralSymbols->getSymbols()) - 1;

        $this->assertEquals('円', $currencyWithSeveralSymbols->getSymbol($lastIndex));
    }

    public function testGettingRandomCurrencySymbolOutOfSeveralOnesThrowsAnException(): void
    {
        $this->expectException(NoSuchCurrencySymbolException::class);

        $currencyWithSeveralSymbols = currency('JPY');
        $outOfBoundsIndex = count($currencyWithSeveralSymbols->getSymbols());

        $currencyWithSeveralSymbols->getSymbol($outOfBoundsIndex);
    }

    public function testNothingHappensOnSettingPreferredCurrencySymbolWhenThereIsOnlyOne(): void
    {
        $currencyWithOneSymbol = currency('USD');
        $randomIndex = 1234;

        $currencyWithOneSymbol->setPreferredSymbol($randomIndex);

        $this->assertEquals('$', $currencyWithOneSymbol->getSymbol());
    }

    public function testGettingPreferredCurrencySymbolOutOfSeveralOnes(): void
    {
        $currencyWithSeveralSymbols = currency('JPY');

        $currencyWithSeveralSymbols->setPreferredSymbol(1);

        $this->assertEquals('円', $currencyWithSeveralSymbols->getSymbol());
    }

    public function testGettingRandomPreferredCurrencySymbolOutOfSeveralOnesThrowsAnException(): void
    {
        $this->expectException(NoSuchCurrencySymbolException::class);

        $currencyWithSeveralSymbols = currency('JPY');
        $outOfBoundsIndex = count($currencyWithSeveralSymbols->getSymbols());

        $currencyWithSeveralSymbols->setPreferredSymbol($outOfBoundsIndex);
    }

    public function testResettingPreferredSymbol()
    {
        $currencyWithSeveralSymbols = currency('JPY');
        $this->assertEquals('¥', $currencyWithSeveralSymbols->getSymbol());

        $currencyWithSeveralSymbols->setPreferredSymbol(1);
        $this->assertEquals('円', $currencyWithSeveralSymbols->getSymbol());

        $currencyWithSeveralSymbols->setPreferredSymbol(null);
        $this->assertEquals('¥', $currencyWithSeveralSymbols->getSymbol());
    }

    public function testGettingAnArrayOfSymbols(): void
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
