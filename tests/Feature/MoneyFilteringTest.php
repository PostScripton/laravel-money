<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyFilteringTest extends TestCase
{
    /** @test */
    public function selectTheMinMoneyOutOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('30');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        $min = Money::min($m1, $m2, $m3);

        $this->assertTrue($min->equals($m2));
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToMinFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::min($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToMinFunction(): void
    {
        $this->assertNull(Money::min());
    }

    /** @test */
    public function selectTheMaxMoneyOutOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('20');
        $m2 = money_parse('30');
        $m3 = money_parse('10');

        $max = Money::max($m1, $m2, $m3);

        $this->assertTrue($max->equals($m2));
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToMaxFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::max($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToMaxFunction(): void
    {
        $this->assertNull(Money::max());
    }

    /** @test */
    public function getAnAverageMoneyOutOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('30');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        $avg = Money::avg($m1, $m2, $m3);

        $this->assertTrue(money_parse('20')->equals($avg));
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToAvgFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::avg($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToAvgFunction(): void
    {
        $this->assertNull(Money::avg());
    }

    /** @test */
    public function getASumOfTheManyMoneyObjects(): void
    {
        $m1 = money_parse('30');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        $avg = Money::sum($m1, $m2, $m3);

        $this->assertTrue(money_parse('60')->equals($avg));
    }

    /** @test */
    public function anExceptionIsThrownWhenDifferentCurrenciesPassedToSumFunction(): void
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1 = money_parse('30', 'RUB');
        $m2 = money_parse('10');
        $m3 = money_parse('20');

        Money::sum($m1, $m2, $m3);
    }

    /** @test */
    public function nullIsGivenWhenNoMoneyObjectsPassedToSumFunction(): void
    {
        $this->assertNull(Money::sum());
    }

    /**
     * @test
     * @dataProvider correctInputDataProvider
     */
    public function correctInput(string $input, string $output): void
    {
        $result = Money::correctInput($input);

        $this->assertEquals($output, $result);
    }

    protected function correctInputDataProvider(): array
    {
        return [
            [
                'input' => '1234.567890',
                'output' => '1234.5',
            ],
            [
                'input' => '1234',
                'output' => '1234',
            ],
        ];
    }
}
