<?php

namespace PostScripton\Money\Tests\Unit;

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

        $this->assertTrue($money1->equals($money2));
        $this->assertTrue($money1->equals($money3));
        $this->assertTrue($money1->equals($money4));
        $this->assertTrue($money1->equals($money5));
        $this->assertEquals('$ 1 234.5', $money5->toString());
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

    /** @test */
    public function moneyGetsRidOfDecimalsWithFloorMethod(): void
    {
        $money = new Money('102500');

        $this->assertEquals('102500', $money->getAmount());
        $this->assertEquals('$ 10.3', $money->toString());

        $money->floor();

        $this->assertEquals('100000', $money->getAmount());
        $this->assertEquals('$ 10', $money->toString());
    }

    /** @test */
    public function moneyGetsRidOfDecimalsWithCeilMethod(): void
    {
        $money = new Money('102500');

        $this->assertEquals('102500', $money->getAmount());
        $this->assertEquals('$ 10.3', $money->toString());

        $money->ceil();

        $this->assertEquals('110000', $money->getAmount());
        $this->assertEquals('$ 11', $money->toString());
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
}
