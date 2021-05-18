<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\NotNumericOrMoneyException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

class MoneyLogicalOperationsTest extends TestCase
{
    /** @test */
    public function money_is_zero()
    {
        $money = new Money(0);

        $this->assertTrue($money->isEmpty());
    }

    /** @test */
    public function money_is_less_than_a_number()
    {
        $money = new Money(500);

        $this->assertTrue($money->lessThan(1000));
        $this->assertTrue($money->lessThan(100.0, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function money_is_less_than_another_money()
    {
        $m1 = new Money(500);
        $m2 = new Money(1000);

        $this->assertTrue($m1->lessThan($m2));
    }

    /** @test */
    public function an_exception_is_thrown_when_anything_else_passes_to_argument_of_money_is_less_than()
    {
        $this->expectException(NotNumericOrMoneyException::class);

        $money = new Money(500);
        $money->lessThan('asdf');
    }

    /** @test */
    public function money_is_less_than_or_equal_to_a_number()
    {
        $money = new Money(500);

        $this->assertTrue($money->lessThanOrEqual(500));
        $this->assertTrue($money->lessThanOrEqual(50.0, MoneySettings::ORIGIN_FLOAT));

        $this->assertTrue($money->lessThanOrEqual(1000));
        $this->assertTrue($money->lessThanOrEqual(100.0, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function money_is_less_than_or_equal_to_another_money()
    {
        $m1 = new Money(500);
        $m2 = new Money(500);
        $m3 = new Money(1000);

        $this->assertTrue($m1->lessThanOrEqual($m2));
        $this->assertTrue($m1->lessThanOrEqual($m3));
    }

    /** @test */
    public function an_exception_is_thrown_when_anything_else_passes_to_argument_of_money_is_less_than_or_equal()
    {
        $this->expectException(NotNumericOrMoneyException::class);

        $money = new Money(500);
        $money->lessThanOrEqual('asdf');
    }

    /** @test */
    public function money_is_greater_than_a_number()
    {
        $money = new Money(1000);

        $this->assertTrue($money->greaterThan(500));
        $this->assertTrue($money->greaterThan(50.0, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function money_is_greater_than_another_money()
    {
        $m1 = new Money(1000);
        $m2 = new Money(500);

        $this->assertTrue($m1->greaterThan($m2));
    }

    /** @test */
    public function an_exception_is_thrown_when_anything_else_passes_to_argument_of_money_is_greater_than()
    {
        $this->expectException(NotNumericOrMoneyException::class);

        $money = new Money(500);
        $money->greaterThan('asdf');
    }

    /** @test */
    public function money_is_greater_than_or_equal_to_a_number()
    {
        $money = new Money(1000);

        $this->assertTrue($money->greaterThanOrEqual(1000));
        $this->assertTrue($money->greaterThanOrEqual(100.0, MoneySettings::ORIGIN_FLOAT));

        $this->assertTrue($money->greaterThanOrEqual(500));
        $this->assertTrue($money->greaterThanOrEqual(50.0, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function money_is_greater_than_or_equal_to_another_money()
    {
        $m1 = new Money(1000);
        $m2 = new Money(1000);
        $m3 = new Money(500);

        $this->assertTrue($m1->greaterThanOrEqual($m2));
        $this->assertTrue($m1->greaterThanOrEqual($m3));
    }

    /** @test */
    public function an_exception_is_thrown_when_anything_else_passes_to_argument_of_money_is_greater_than_or_equal()
    {
        $this->expectException(NotNumericOrMoneyException::class);

        $money = new Money(500);
        $money->greaterThanOrEqual('asdf');
    }

    /** @test */
    public function money_has_the_same_currency_with_another_money()
    {
        $usd1 = new Money(1000);
        $usd2 = new Money(1000);
        $rub = new Money(1000, Currency::code('RUB'));

        $this->assertTrue($usd1->isSameCurrency($usd2));
        $this->assertFalse($usd1->isSameCurrency($rub));
    }

    /** @test */
    public function money_is_positive()
    {
        $money = new Money(1000);

        $this->assertTrue($money->isPositive());
    }

    /** @test */
    public function money_is_negative()
    {
        $money = new Money(-1000);

        $this->assertTrue($money->isNegative());
    }

    /** @test */
    public function check_if_money_objects_are_the_same()
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