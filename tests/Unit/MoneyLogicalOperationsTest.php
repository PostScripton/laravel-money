<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Tests\TestCase;

class MoneyLogicalOperationsTest extends TestCase
{
    /** @test */
    public function moneyIsZero(): void
    {
        $m1 = money('0');
        $m2 = money('0.0000');
        $m3 = money('0.1234');

        $this->assertTrue($m1->isZero());
        $this->assertTrue($m2->isZero());
        $this->assertTrue($m3->isZero());
    }

    /** @test */
    public function moneyIsLessThanAnotherMoney(): void
    {
        $m1 = money('500000');
        $m2 = money('1000000');

        $this->assertTrue($m1->lessThan($m2));

        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m3 = money('1000000', 'RUB');
        $m1->lessThan($m3);
    }

    /** @test */
    public function moneyIsLessThanOrEqualToAnotherMoney(): void
    {
        $m1 = money('500000');
        $m2 = money('500000');
        $m3 = money('1000000');

        $this->assertTrue($m1->lessThanOrEqual($m2));
        $this->assertTrue($m1->lessThanOrEqual($m3));

        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m4 = money('1000000', 'RUB');
        $m1->lessThanOrEqual($m4);
    }

    /** @test */
    public function moneyIsGreaterThanAnotherMoney(): void
    {
        $m1 = money('1000000');
        $m2 = money('500000');

        $this->assertTrue($m1->greaterThan($m2));

        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m3 = money('500000', 'RUB');
        $m1->greaterThan($m3);
    }

    /** @test */
    public function moneyIsGreaterThanOrEqualToAnotherMoney(): void
    {
        $m1 = money('1000000');
        $m2 = money('1000000');
        $m3 = money('500000');

        $this->assertTrue($m1->greaterThanOrEqual($m2));
        $this->assertTrue($m1->greaterThanOrEqual($m3));

        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m4 = money('500000', 'RUB');
        $m1->greaterThanOrEqual($m4);
    }

    /** @test */
    public function moneyHasTheSameCurrencyWithAnotherMoney(): void
    {
        $usd1 = money('1000000');
        $usd2 = money('1000000');
        $rub = money('1000000', Currency::code('RUB'));

        $this->assertTrue($usd1->isSameCurrency($usd2));
        $this->assertFalse($usd1->isSameCurrency($rub));
    }

    /** @test */
    public function moneyHasDifferentCurrencyFromAnotherMoney(): void
    {
        $usd1 = money('1000000');
        $usd2 = money('1000000');
        $rub = money('1000000', Currency::code('RUB'));

        $this->assertFalse($usd1->isDifferentCurrency($usd2));
        $this->assertTrue($usd1->isDifferentCurrency($rub));
    }

    /** @test */
    public function moneyIsPositive(): void
    {
        $money = money('1000000');

        $this->assertTrue($money->isPositive());
    }

    /** @test */
    public function moneyIsNegative(): void
    {
        $money = money('-1000000');

        $this->assertTrue($money->isNegative());
    }

    /** @test */
    public function zeroMoneyIsNotBothPositiveAndNegative(): void
    {
        $money = money('0');

        $this->assertFalse($money->isPositive());
        $this->assertFalse($money->isNegative());
    }

    /** @test */
    public function checkIfMoneyObjectsAreTheSame(): void
    {
        $m1 = money('12345000');
        $m2 = money('12345000');
        $m3 = money('12345000', currency('RUB'));

        $this->assertTrue($m1->equals($m2));
        $this->assertFalse($m1->equals($m3));
    }
}
