<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Tests\TestCase;

class MoneyLogicalOperationsTest extends TestCase
{
    /** @test */
    public function moneyIsZero()
    {
        $money = money('0');

        $this->assertTrue($money->isEmpty());
    }

    /** @test */
    public function moneyIsLessThanAnotherMoney()
    {
        $m1 = money('500000');
        $m2 = money('1000000');

        $this->assertTrue($m1->lessThan($m2));
    }

    /** @test */
    public function moneyIsLessThanOrEqualToAnotherMoney()
    {
        $m1 = money('500000');
        $m2 = money('500000');
        $m3 = money('1000000');

        $this->assertTrue($m1->lessThanOrEqual($m2));
        $this->assertTrue($m1->lessThanOrEqual($m3));
    }

    /** @test */
    public function moneyIsGreaterThanAnotherMoney()
    {
        $m1 = money('1000000');
        $m2 = money('500000');

        $this->assertTrue($m1->greaterThan($m2));
    }

    /** @test */
    public function moneyIsGreaterThanOrEqualToAnotherMoney()
    {
        $m1 = money('1000000');
        $m2 = money('1000000');
        $m3 = money('500000');

        $this->assertTrue($m1->greaterThanOrEqual($m2));
        $this->assertTrue($m1->greaterThanOrEqual($m3));
    }

    /** @test */
    public function moneyHasTheSameCurrencyWithAnotherMoney()
    {
        $usd1 = money('1000000');
        $usd2 = money('1000000');
        $rub = money('1000000', Currency::code('RUB'));

        $this->assertTrue($usd1->isSameCurrency($usd2));
        $this->assertFalse($usd1->isSameCurrency($rub));
    }

    /** @test */
    public function moneyIsPositive()
    {
        $money = money('1000000');

        $this->assertTrue($money->isPositive());
    }

    /** @test */
    public function moneyIsNegative()
    {
        $money = money('-1000000');

        $this->assertTrue($money->isNegative());
    }

    /** @test */
    public function zeroMoneyIsNotBothPositiveAndNegative()
    {
        $money = money('0');

        $this->assertFalse($money->isPositive());
        $this->assertFalse($money->isNegative());
    }

    /** @test */
    public function checkIfMoneyObjectsAreTheSame()
    {
        $m1 = money('12345000');
        $m2 = money('12345000');
        $m3 = money('12345000', currency('RUB'));

        $this->assertTrue($m1->equals($m2));
        $this->assertFalse($m1->equals($m3));
    }
}
