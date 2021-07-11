<?php

namespace PostScripton\Money\Tests\Feature;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Tests\TestCase;

class CurrencyListsTest extends TestCase
{
    private $backup_config;

    /** @test */
    public function gettingCurrenciesFromThePopularList()
    {
        Currency::setCurrencyList(Currency::LIST_POPULAR);

        $this->assertEquals('USD', Currency::code('840')->getCode());
        $this->assertEquals('EUR', Currency::code('EUR')->getCode());
        $this->assertEquals('RUB', Currency::code('RUB')->getCode());
    }

    /** @test */
    public function gettingCurrenciesFromTheAllList()
    {
        Currency::setCurrencyList(Currency::LIST_ALL);

        $this->assertEquals('AFN', Currency::code('AFN')->getCode());
        $this->assertEquals('ALL', Currency::code('ALL')->getCode());
        $this->assertEquals('AMD', Currency::code('AMD')->getCode());
    }

    /** @test */
    public function gettingCurrenciesFromTheConfigList()
    {
        Config::set('money.custom_currencies', [
            [
                'full_name' => 'Bitcoin',
                'name' => 'BTC',
                'iso_code' => 'XBT',
                'num_code' => '1234',
                'symbol' => '₿',
                'position' => Currency::POSITION_START,
            ],
        ]);

        Currency::setCurrencyList(Currency::LIST_CUSTOM);

        $btc = currency('xbt');
        $this->assertInstanceOf(Currency::class, $btc);
        $this->assertEquals('1234', $btc->getNumCode());

        // No access to ALL list
        $this->expectException(CurrencyDoesNotExistException::class);
        currency('AFN');
    }

    /** @test */
    public function aUserCanSelectCurrenciesHeNeeds()
    {
        $backup_config = config('money.currency_list');
        config(['money.currency_list' => ['840', 'EUR', 'RUB']]);

        Currency::setCurrencyList(Currency::LIST_CONFIG);
        $this->assertEquals(Currency::LIST_CONFIG, Currency::currentList());

        // Currency from the list
        $this->assertInstanceOf(Currency::class, Currency::code('usd'));
        $this->assertEquals('$', Currency::code('usd')->getSymbol());

        // Currency out of the list
        $this->expectException(CurrencyDoesNotExistException::class);
        Currency::code('PHP');

        config(['money.currency_list' => $backup_config]);
    }

    /** @test */
    public function switchingBackToTheConfigList()
    {
        $backup_config = config('money.currency_list');
        config(['money.currency_list' => ['840', 'EUR', 'RUB']]);

        Currency::setCurrencyList(Currency::LIST_ALL);
        $this->assertEquals(Currency::LIST_ALL, Currency::currentList());

        // Found because from 'all' list
        $this->assertInstanceOf(Currency::class, Currency::code('php'));
        $this->assertEquals('608', Currency::code('php')->getNumCode());

        // Back to config
        Currency::setCurrencyList(Currency::LIST_CONFIG);
        $this->assertEquals(Currency::LIST_CONFIG, Currency::currentList());

        // Currency from the list
        $this->assertInstanceOf(Currency::class, Currency::code('usd'));
        $this->assertEquals('$', Currency::code('usd')->getSymbol());

        // Not found because out of config list
        $this->expectException(CurrencyDoesNotExistException::class);
        Currency::code('PHP');

        config(['money.currency_list' => $backup_config]);
    }

    /** @test */
    public function anExceptionIsThrowTryingToGetACurrencyFromAllListInThePopularOne()
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currency::setCurrencyList(Currency::LIST_POPULAR);
        $this->assertEquals('AFN', Currency::code('AFN')->getCode());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->backup_config = Config::get('money');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backup_config);
    }
}
