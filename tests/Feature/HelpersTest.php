<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function create_money_with_money_helper()
    {
        $money = money(12345);

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals(12345, $money->getPureAmount());
    }

    /** @test */
    public function create_money_with_money_and_currency_helpers()
    {
        $money = money(12345, currency('RUB'));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('₽', $money->getCurrency()->getSymbol());
        $this->assertEquals('RUB', $money->getCurrency()->getCode());
    }

    /** @test */
    public function create_money_with_money_currency_and_settings_helpers()
    {
        $money = money(12345, currency('RUB'), settings()->setHasSpaceBetween(false));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5₽', $money->toString());
        $this->assertFalse($money->settings()->hasSpaceBetween());
    }

    /** @test */
    public function modify_currency_before_creating_money()
    {
        $money = money(12345, currency('usd')->setPosition(Currency::POSITION_END));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5 $', $money->toString());
        $this->assertEquals(Currency::POSITION_END, $money->getCurrency()->getPosition());
    }
}