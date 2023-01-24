<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyFilteringTest extends TestCase
{
    public function testSelectTheMinMoneyOutOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('30');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        $min = Money::min($m1, $m2, $m3);

        $this->assertTrue($min->equals($m2));
    }

    public function testExceptionIsThrownWhenDifferentCurrenciesPassedToMinFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::min($m1, $m2, $m3);
    }

    public function testNullIsGivenWhenNoMoneyObjectsPassedToMinFunction(): void
    {
        $this->assertNull(Money::min());
    }

    public function testSelectTheMaxMoneyOutOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('20');
        $m2 = money_parse('30');
        $m3 = money_parse('10');

        $max = Money::max($m1, $m2, $m3);

        $this->assertTrue($max->equals($m2));
    }

    public function testExceptionIsThrownWhenDifferentCurrenciesPassedToMaxFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::max($m1, $m2, $m3);
    }

    public function testNullIsGivenWhenNoMoneyObjectsPassedToMaxFunction(): void
    {
        $this->assertNull(Money::max());
    }

    public function testGetAnAverageMoneyOutOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('30');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        $avg = Money::avg($m1, $m2, $m3);

        $this->assertTrue(money_parse('20')->equals($avg));
    }

    public function testExceptionIsThrownWhenDifferentCurrenciesPassedToAvgFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::avg($m1, $m2, $m3);
    }

    public function testNullIsGivenWhenNoMoneyObjectsPassedToAvgFunction(): void
    {
        $this->assertNull(Money::avg());
    }

    public function testGetASumOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('30');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        $avg = Money::sum($m1, $m2, $m3);

        $this->assertTrue(money_parse('60')->equals($avg));
    }

    public function testExceptionIsThrownWhenDifferentCurrenciesPassedToSumFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::sum($m1, $m2, $m3);
    }

    public function testNullIsGivenWhenNoMoneyObjectsPassedToSumFunction(): void
    {
        $this->assertNull(Money::sum());
    }
}
