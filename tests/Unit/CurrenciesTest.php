<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currencies;
use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyList;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
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
    public function getCodesArray(): void
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

    /** @test */
    public function same(): void
    {
        // Always true
        $this->assertTrue(Currencies::same());
        $this->assertTrue(Currencies::same(currency('USD')));

        $this->assertTrue(Currencies::same(
            currency('USD'),
            currency('USD'),
        ));
        $this->assertFalse(Currencies::same(
            currency('USD'),
            currency('RUB'),
        ));
        $this->assertFalse(Currencies::same(
            currency('USD'),
            currency('RUB'),
            currency('USD'),
        ));
    }

    /** @test */
    public function sameWithDifferentTypeArguments()
    {
        $this->assertTrue(Currencies::same('USD'));
        $this->assertTrue(Currencies::same('USD', 'USD'));
        $this->assertTrue(Currencies::same(currency('USD'), 'USD'));
        $this->assertFalse(Currencies::same('USD', 'RUB', currency('USD')));
    }

    /** @test */
    public function sameThrowsAnExceptionOnPassingNonSense()
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currencies::same('USD', 1234.56, 'USD', true);
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
