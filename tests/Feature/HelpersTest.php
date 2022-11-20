<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function createMoneyWithMoneyHelper(): void
    {
        $money = money('12345000');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('12345000', $money->getAmount());
    }

    /** @test */
    public function createMoneyWithMoneyAndCurrencyHelpers(): void
    {
        $money = money('12345000', currency('RUB'));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('₽', $money->getCurrency()->getSymbol());
        $this->assertEquals('RUB', $money->getCurrency()->getCode());
    }

    /** @test */
    public function modifyCurrencyBeforeCreatingMoney(): void
    {
        $money = money('12345000', currency('usd')->setPosition(CurrencyPosition::End));

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5 $', $money->toString());
        $this->assertEquals(CurrencyPosition::End, $money->getCurrency()->getPosition());
    }
}
