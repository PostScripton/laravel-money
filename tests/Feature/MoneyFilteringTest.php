<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyFilteringTest extends TestCase
{
    /** @test */
    public function selectTheMinMoneyOutOfTheManyMoneyObjects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $min = Money::min($m1, $m2, $m3);

        $this->assertTrue($min->equals($m2));
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToMinFunction()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::min($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToMinFunction()
    {
        $this->assertNull(Money::min());
    }

    /** @test */
    public function selectTheMaxMoneyOutOfTheManyMoneyObjects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $min = Money::max($m1, $m2, $m3);

        $this->assertTrue($min->equals($m1));
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToMaxFunction()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::max($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToMaxFunction()
    {
        $this->assertNull(Money::max());
    }

    /** @test */
    public function getAnAverageMoneyOutOfTheManyMoneyObjects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $avg = Money::avg($m1, $m2, $m3);

        $this->assertEquals(2000, $avg->getPureAmount());
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToAvgFunction()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::avg($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToAvgFunction()
    {
        $this->assertNull(Money::avg());
    }

    /** @test */
    public function getASumOfTheManyMoneyObjects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $avg = Money::sum($m1, $m2, $m3);

        $this->assertEquals(6000, $avg->getPureAmount());
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToSumFunction()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::sum($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToSumFunction()
    {
        $this->assertNull(Money::sum());
    }
}
