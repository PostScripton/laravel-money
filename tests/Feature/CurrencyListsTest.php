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
    public function CurrencyFromALlInPopularException()
    {
        $this->expectException(CurrencyDoesNotExistException::class);

        Currency::setCurrencyList(Currency::LIST_POPULAR);
        $this->assertEquals('AFN', Currency::code('AFN')->getCode());
    }
}