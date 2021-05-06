<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;

class CurrencyListsTest extends TestCase
{
    /** @test */
    public function PopularList()
    {
        Currency::setCurrencyList(Currency::LIST_POPULAR);

        $this->assertEquals('USD', Currency::code('840')->getCode());
        $this->assertEquals('EUR', Currency::code('EUR')->getCode());
        $this->assertEquals('RUB', Currency::code('RUB')->getCode());
    }

    /** @test */
    public function AllList()
    {
        Currency::setCurrencyList(Currency::LIST_ALL);

        $this->assertEquals('AFN', Currency::code('AFN')->getCode());
        $this->assertEquals('ALL', Currency::code('ALL')->getCode());
        $this->assertEquals('AMD', Currency::code('AMD')->getCode());
    }
    
    /** @test */
    public function CustomList()
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
    public function BackToConfigList()
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
    public function CurrencyFromALlInPopularException()
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currency::setCurrencyList(Currency::LIST_POPULAR);
        $this->assertEquals('AFN', Currency::code('AFN')->getCode());
    }
}