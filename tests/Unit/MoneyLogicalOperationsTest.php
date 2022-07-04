<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Tests\TestCase;

class MoneyLogicalOperationsTest extends TestCase
{
    /** @test */
    public function moneyIsZero()
    {
        $money = new Money(0);

        $this->assertTrue($money->isEmpty());
    }

    /** @test */
    public function moneyIsLessThanAnotherMoney()
    {
        $m1 = new Money(500);
        $m2 = new Money(1000);

        $this->assertTrue($m1->lessThan($m2));
    }

    /** @test */
    public function moneyIsLessThanOrEqualToAnotherMoney()
    {
        $m1 = new Money(500);
        $m2 = new Money(500);
        $m3 = new Money(1000);

        $this->assertTrue($m1->lessThanOrEqual($m2));
        $this->assertTrue($m1->lessThanOrEqual($m3));
    }

    /** @test */
    public function moneyIsGreaterThanAnotherMoney()
    {
        $m1 = new Money(1000);
        $m2 = new Money(500);

        $this->assertTrue($m1->greaterThan($m2));
    }

    /** @test */
    public function moneyIsGreaterThanOrEqualToAnotherMoney()
    {
        $m1 = new Money(1000);
        $m2 = new Money(1000);
        $m3 = new Money(500);

        $this->assertTrue($m1->greaterThanOrEqual($m2));
        $this->assertTrue($m1->greaterThanOrEqual($m3));
    }

    /** @test */
    public function moneyHasTheSameCurrencyWithAnotherMoney()
    {
        $usd1 = new Money(1000);
        $usd2 = new Money(1000);
        $rub = new Money(1000, Currency::code('RUB'));

        $this->assertTrue($usd1->isSameCurrency($usd2));
        $this->assertFalse($usd1->isSameCurrency($rub));
    }

    /** @test */
    public function moneyIsPositive()
    {
        $money = new Money(1000);

        $this->assertTrue($money->isPositive());
    }

    /** @test */
    public function moneyIsNegative()
    {
        $money = new Money(-1000);

        $this->assertTrue($money->isNegative());
    }

    /** @test */
    public function zeroMoneyIsNotBothPositiveAndNegative()
    {
        $money = new Money(0);

        $this->assertFalse($money->isPositive());
        $this->assertFalse($money->isNegative());
    }

    /** @test */
    public function checkIfMoneyObjectsAreTheSame()
    {
        $m1 = $m2 = new Money(1000);
        $m3 = new Money(1000);
        $m4 = new Money(1500);

        $this->assertTrue($m1->equals($m2));
        $this->assertTrue($m1->equals($m3, false));
        $this->assertFalse($m1->equals($m3));
        $this->assertFalse($m1->equals($m4));
    }
}
