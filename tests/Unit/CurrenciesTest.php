<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currencies;
use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyList;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class CurrenciesTest extends TestCase
{
    use InteractsWithConfig;

    /** @test */
    public function getAllCurrencies(): void
    {
        Config::set(['money.currency_list' => CurrencyList::All]);

        $collection = Currencies::get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotEmpty($collection);
        $this->assertCount(154, $collection);
    }

    /** @test */
    public function getPopularCurrencies(): void
    {
        Config::set(['money.currency_list' => CurrencyList::Popular]);

        $collection = Currencies::get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotEmpty($collection);
        $this->assertCount(35, $collection);
    }

    /** @test */
    public function getCustomCurrencies(): void
    {
        Config::set(['money.currency_list' => CurrencyList::Custom]);
        Config::set([
            'money.custom_currencies' => [
                [
                    'full_name' => 'Testing currency',
                    'name' => 'test',
                    'iso_code' => 'TST',
                    'num_code' => '999',
                    'symbol' => '&',
                    'position' => CurrencyPosition::Start,
                ],
            ],
        ]);

        $collection = Currencies::get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotEmpty($collection);
        $this->assertCount(1, $collection);
    }

    /** @test */
    public function getEmptyCollectionOnCustomCurrencies(): void
    {
        Config::set(['money.currency_list' => CurrencyList::Custom]);
        Config::set(['money.custom_currencies' => []]);

        $collection = Currencies::get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEmpty($collection);
        $this->assertCount(0, $collection);
    }

    /** @test */
    public function getSpecificCurrenciesFromCurrencyList(): void
    {
        $desiredCurrencies = ['840', 'EUR', 'RUB', '999'];
        Config::set(['money.currency_list' => $desiredCurrencies]);
        Config::set([
            'money.custom_currencies' => [
                [
                    'full_name' => 'Testing currency',
                    'name' => 'test',
                    'iso_code' => 'TST',
                    'num_code' => '999',
                    'symbol' => '&',
                    'position' => CurrencyPosition::Start,
                ],
            ],
        ]);

        $collection = Currencies::get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertNotEmpty($collection);
        $this->assertCount(4, $collection);
        foreach ($desiredCurrencies as $desiredCurrency) {
            $currency = $collection->first(function (Currency $currency) use ($desiredCurrency) {
                return $currency->getCode() === $desiredCurrency || $currency->getNumCode() === $desiredCurrency;
            });
            $this->assertNotNull($currency);
        }
    }

    /** @test */
    public function noSpecificCurrenciesInCurrencyList(): void
    {
        $desiredCurrencies = [];
        Config::set(['money.currency_list' => $desiredCurrencies]);

        $collection = Currencies::get();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEmpty($collection);
        $this->assertCount(0, $collection);
    }

    /** @test */
    public function customCurrenciesOverrideDefaultOnes(): void
    {
        $expectedFullName = 'My testing dollar';
        $expectedName = 'dollar';
        $expectedSymbol = '$$$';
        $expectedPosition = CurrencyPosition::End;
        Config::set(['money.currency_list' => CurrencyList::Popular]);
        Config::set([
            'money.custom_currencies' => [
                [
                    'full_name' => $expectedFullName,
                    'name' => $expectedName,
                    'iso_code' => 'USD',
                    'num_code' => '999',
                    'symbol' => $expectedSymbol,
                    'position' => $expectedPosition,
                ],
            ],
        ]);

        $currency = currency('USD');

        $this->assertEquals($expectedFullName, $currency->getFullName());
        $this->assertEquals($expectedName, $currency->getName());
        $this->assertEquals($expectedSymbol, $currency->getSymbol());
        $this->assertEquals($expectedPosition, $currency->getPosition());
    }

    /** @test */
    public function getCodesArray1234(): void
    {
        $expectedCurrencies = ['USD', 'EUR', 'RUB', 'TST'];
        Config::set(['money.currency_list' => $expectedCurrencies]);
        Config::set([
            'money.custom_currencies' => [
                [
                    'full_name' => 'Testing currency',
                    'name' => 'test',
                    'iso_code' => 'TST',
                    'num_code' => '999',
                    'symbol' => '&',
                    'position' => CurrencyPosition::Start,
                ],
            ],
        ]);

        $codes = Currencies::getCodesArray();

        $this->assertEquals($expectedCurrencies, $codes);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpConfig();

        $this->currencies()::reset();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tearDownConfig();

        $this->currencies()::reset();
    }

    private function currencies(): Currencies
    {
        return new class extends Currencies {
            public static function reset(): void
            {
                self::$currencies = null;
            }
        };
    }
}
