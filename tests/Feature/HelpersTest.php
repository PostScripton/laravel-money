<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function createMoneyWithMoneyHelper()
    {
        $money = money('12345000');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('12345000', $money->getPureAmount());
    }

    /** @test */
    public function createMoneyWithMoneyAndCurrencyHelpers()
    {
        $money = money('12345000', currency('RUB'));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('₽', $money->getCurrency()->getSymbol());
        $this->assertEquals('RUB', $money->getCurrency()->getCode());
    }

    /** @test */
    public function createMoneyWithMoneyCurrencyAndSettingsHelpers()
    {
        $money = money('12345000', currency('RUB'), settings()->setHasSpaceBetween(false));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5₽', $money->toString());
        $this->assertFalse($money->settings()->hasSpaceBetween());
    }

    /** @test */
    public function modifyCurrencyBeforeCreatingMoney()
    {
        $money = money('12345000', currency('usd')->setPosition(CurrencyPosition::End));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5 $', $money->toString());
        $this->assertEquals(CurrencyPosition::End, $money->getCurrency()->getPosition());
    }
}
