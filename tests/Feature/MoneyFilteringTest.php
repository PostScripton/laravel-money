<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyFilteringTest extends TestCase
{
    /** @test */
    public function select_the_min_money_out_of_the_many_money_objects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $min = Money::min($m1, $m2, $m3);

        $this->assertTrue($min->equals($m2));
    }

    /** @test */
    public function an_exception_is_thrown_when_different_currencies_passed_to_min_function()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::min($m1, $m2, $m3);
    }

    /** @test */
    public function null_is_given_when_no_money_objects_passed_to_min_function()
    {
        $this->assertNull(Money::min());
    }

    /** @test */
    public function select_the_max_money_out_of_the_many_money_objects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $min = Money::max($m1, $m2, $m3);

        $this->assertTrue($min->equals($m1));
    }

    /** @test */
    public function an_exception_is_thrown_when_different_currencies_passed_to_max_function()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::max($m1, $m2, $m3);
    }

    /** @test */
    public function null_is_given_when_no_money_objects_passed_to_max_function()
    {
        $this->assertNull(Money::max());
    }
    
    /** @test */
    public function get_an_average_money_out_of_the_many_money_objects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $avg = Money::avg($m1, $m2, $m3);

        $this->assertEquals(2000, $avg->getPureAmount());
    }

    /** @test */
    public function an_exception_is_thrown_when_different_currencies_passed_to_avg_function()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::avg($m1, $m2, $m3);
    }

    /** @test */
    public function null_is_given_when_no_money_objects_passed_to_avg_function()
    {
        $this->assertNull(Money::avg());
    }

    /** @test */
    public function get_a_sum_of_the_many_money_objects()
    {
        $m1 = new Money(3000);
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        $avg = Money::sum($m1, $m2, $m3);

        $this->assertEquals(6000, $avg->getPureAmount());
    }

    /** @test */
    public function an_exception_is_thrown_when_different_currencies_passed_to_sum_function()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = new Money(3000, Currency::code('RUB'));
        $m2 = new Money(1000);
        $m3 = new Money(2000);

        Money::sum($m1, $m2, $m3);
    }

    /** @test */
    public function null_is_given_when_no_money_objects_passed_to_sum_function()
    {
        $this->assertNull(Money::sum());
    }
}