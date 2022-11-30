<?php

namespace PostScripton\Money\Tests\Unit;

use InvalidArgumentException;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    /** @test */
    public function allTheWaysToCreateMoney(): void
    {
        $money1 = new Money('12345000');
        $money2 = Money::of('12345000');
        $money3 = money('12345000');
        $money4 = money_parse('$ 1234.5');
        $money5 = money('12345000', currency('USD'));
        $money6 = money('12345000.1234567890');

        $this->assertTrue($money1->equals($money2));
        $this->assertTrue($money1->equals($money3));
        $this->assertTrue($money1->equals($money4));
        $this->assertTrue($money1->equals($money5));
        $this->assertTrue($money1->equals($money6));
        $this->assertEquals('12345000', $money6->getAmount());
    }

    /** @dataProvider creatingMoneyWithExceptionDataProvider */
    public function testCreatingMoneyWithNonNumericStringThrowsAnException(string $amount): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('The amount must be a numeric-string, [%s] given', $amount));

        new Money($amount);
    }

    /** @test */
    public function baseWaysOfFormattingMoney(): void
    {
        $usd = Currency::code('USD');
        $rub = Currency::code('RUB');

        $this->assertEquals('$ 123', money('1230000', $usd)->toString());
        $this->assertEquals('$ 123.4', money('1234000', $usd)->toString());
        $this->assertEquals('$ 1 234', money('12340000', $usd)->toString());
        $this->assertEquals('$ 1 234.5', money('12345000', $usd)->toString());

        $this->assertEquals('123 ₽', money('1230000', $rub)->toString());
        $this->assertEquals('123.4 ₽', money('1234000', $rub)->toString());
        $this->assertEquals('1 234 ₽', money('12340000', $rub)->toString());
        $this->assertEquals('1 234.5 ₽', money('12345000', $rub)->toString());
    }

    /** @test */
    public function numbersCanBeFetchedOutOfTheMoney(): void
    {
        $money = Money::of('12345000');

        $this->assertEquals('1 234.5', $money->toAmountOnlyString());
        $this->assertEquals('12345000', $money->getAmount());
    }

    /** @test */
    public function allCastsToString(): void
    {
        $money = Money::of('1234000');

        $this->assertEquals('$ 123.4', $money->toString());
        $this->assertEquals('$ 123.4', strval($money));
        $this->assertEquals('$ 123.4', '' . $money);
        $this->assertEquals('$ 123.4', $money);
    }

    public function testMultiply(): void
    {
        $money = money_parse('100');

        $money->multiply('2');
        $this->assertEquals('2000000', $money->getAmount());
        $this->assertEquals('$ 200', $money->toString());

        $money->multiply('-2');
        $this->assertEquals('-4000000', $money->getAmount());
        $this->assertEquals('$ -400', $money->toString());
    }

    public function testMultiplyByFloat(): void
    {
        $money = money_parse('100');

        $money->multiply(0.5);
        $this->assertEquals('500000', $money->getAmount());
        $this->assertEquals('$ 50', $money->toString());

        $money->multiply(-0.5);
        $this->assertEquals('-250000', $money->getAmount());
        $this->assertEquals('$ -25', $money->toString());
    }

    public function testMultiplyButNoDecimalsInAmount(): void
    {
        $money = money_parse('10');
        $expectedMoney = money_parse('3.3333');

        $money->multiply((string) (1 / 3));

        $this->assertEquals('33333', $money->getAmount());
        $this->assertEquals('$ 3.3', $money->toString());
        $this->assertTrue($expectedMoney->equals($money));
    }

    public function testDivideButNoDecimalsInAmount(): void
    {
        $money = money_parse('100');
        $expectedMoney = money_parse('33.3333');

        $money->divide('3');

        $this->assertEquals('333333', $money->getAmount());
        $this->assertEquals('$ 33.3', $money->toString());
        $this->assertTrue($expectedMoney->equals($money));
    }

    public function testFloor(): void
    {
        $money = new Money('102500');

        $this->assertEquals('102500', $money->getAmount());
        $this->assertEquals('$ 10.3', $money->toString());

        $money->floor();

        $this->assertEquals('100000', $money->getAmount());
        $this->assertEquals('$ 10', $money->toString());
    }

    public function testNegativeFloor(): void
    {
        $money = new Money('-102500');

        $this->assertEquals('-102500', $money->getAmount());
        $this->assertEquals('$ -10.3', $money->toString());

        $money->floor();

        $this->assertEquals('-110000', $money->getAmount());
        $this->assertEquals('$ -11', $money->toString());
    }

    public function testCeil(): void
    {
        $money = new Money('102500');

        $this->assertEquals('102500', $money->getAmount());
        $this->assertEquals('$ 10.3', $money->toString());

        $money->ceil();

        $this->assertEquals('110000', $money->getAmount());
        $this->assertEquals('$ 11', $money->toString());
    }

    public function testNegativeCeil(): void
    {
        $money = new Money('-102500');

        $this->assertEquals('-102500', $money->getAmount());
        $this->assertEquals('$ -10.3', $money->toString());

        $money->ceil();

        $this->assertEquals('-100000', $money->getAmount());
        $this->assertEquals('$ -10', $money->toString());
    }

    /** @test */
    public function rebaseMoney(): void
    {
        $m1 = money_parse('100');
        $m2 = money_parse('250');

        $m1->rebase($m2);

        $this->assertTrue($m1->equals(money_parse('250')));
        $this->assertEquals('2500000', $m1->getAmount());
        $this->assertEquals('$ 250', $m1->toString());
    }

    /** @test */
    public function addingMoneyWithDifferentCurrencyThrowsException(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('100');
        $m2 = money_parse('250', 'RUB');

        $m1->add($m2);
    }

    /** @test */
    public function subtractingMoneyWithDifferentCurrencyThrowsException(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('100');
        $m2 = money_parse('250', 'RUB');

        $m1->subtract($m2);
    }

    /** @test */
    public function rebasingMoneyWithDifferentCurrencyThrowsException(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('100');
        $m2 = money_parse('250', 'RUB');

        $m1->rebase($m2);
    }

    /**
     * @test
     * @dataProvider absoluteDataProvider
     */
    public function moneyAbsoluteAmount(string $negative, string $absolute): void
    {
        $money = money($negative);

        $money->absolute();

        $this->assertEquals($absolute, $money->getAmount());
    }

    /** @test */
    public function correctWayToHandleImmutableMoneyObjects(): void
    {
        $m1 = money_parse('100');

        $m2 = $m1
            // adds to the both
            ->add(money_parse('50'))
            // $m2 is $150 as long as $m1 but $m2 is independent now
            ->clone()
            // $m2 is $300 whereas $m1 is still $150
            ->multiply(2);

        $this->assertEquals('1500000', $m1->getAmount());
        $this->assertEquals('3000000', $m2->getAmount());
        $this->assertFalse($m1->equals($m2));
    }

    protected function absoluteDataProvider(): array
    {
        return [
            [
                'negative' => '-12345',
                'absolute' => '12345',
            ],
            [
                'negative' => '12345',
                'absolute' => '12345',
            ],
            [
                'negative' => '-0',
                'absolute' => '0',
            ],
        ];
    }

    protected function creatingMoneyWithExceptionDataProvider(): array
    {
        return [
            ['amount' => 'qwerty'],
            ['amount' => '$ 1 234.5'],
            ['amount' => '$ 1234.5'],
        ];
    }
}
