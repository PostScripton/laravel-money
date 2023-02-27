<?php

namespace PostScripton\Money\Tests\Unit\Traits;

use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class StaticPartTest extends TestCase
{
    /** @dataProvider providerMin */
    public function testMin(array $args, string|null $expectedMin): void
    {
        if ($this->isExceptionClass($expectedMin)) {
            $this->expectException($expectedMin);
        }

        $min = Money::min(...$args);

        if ($this->isExceptionClass($expectedMin)) {
            return;
        }

        if (is_null($expectedMin)) {
            $this->assertNull($min);
            return;
        }

        $this->assertMoneyEquals(money_parse($expectedMin), $min);
    }

    public function providerMin(): array
    {
        $this->createApplication();

        return [
            [
                'args' => [
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedMin' => '10',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                ],
                'expectedMin' => '10',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                    money_parse('40'),
                ],
                'expectedMin' => '10',
            ],
            [
                'args' => [
                    money_parse('30', 'RUB'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedMin' => MoneyHasDifferentCurrenciesException::class,
            ],
            [
                'args' => [
                    null,
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedMin' => '10',
            ],
            [
                'args' => [
                    collect(),
                ],
                'expectedMin' => null,
            ],
            [
                'args' => [],
                'expectedMin' => null,
            ],
        ];
    }

    /** @dataProvider providerMax */
    public function testMax(array $args, string|null $expectedMax): void
    {
        if ($this->isExceptionClass($expectedMax)) {
            $this->expectException($expectedMax);
        }

        $max = Money::max(...$args);

        if ($this->isExceptionClass($expectedMax)) {
            return;
        }

        if (is_null($expectedMax)) {
            $this->assertNull($max);
            return;
        }

        $this->assertMoneyEquals(money_parse($expectedMax), $max);
    }

    public function providerMax(): array
    {
        $this->createApplication();

        return [
            [
                'args' => [
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedMax' => '30',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                ],
                'expectedMax' => '30',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                    money_parse('40'),
                ],
                'expectedMax' => '40',
            ],
            [
                'args' => [
                    money_parse('30', 'RUB'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedMax' => MoneyHasDifferentCurrenciesException::class,
            ],
            [
                'args' => [
                    null,
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedMax' => '30',
            ],
            [
                'args' => [
                    collect(),
                ],
                'expectedMax' => null,
            ],
            [
                'args' => [],
                'expectedMax' => null,
            ],
        ];
    }

    /** @dataProvider providerAvg */
    public function testAvg(array $args, string|null $expectedAvg): void
    {
        if ($this->isExceptionClass($expectedAvg)) {
            $this->expectException($expectedAvg);
        }

        $avg = Money::avg(...$args);

        if ($this->isExceptionClass($expectedAvg)) {
            return;
        }

        if (is_null($expectedAvg)) {
            $this->assertNull($avg);
            return;
        }

        $this->assertMoneyEquals(money_parse($expectedAvg), $avg);
    }

    public function providerAvg(): array
    {
        $this->createApplication();

        return [
            [
                'args' => [
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedAvg' => '20',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                ],
                'expectedAvg' => '20',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                    money_parse('40'),
                ],
                'expectedAvg' => '25',
            ],
            [
                'args' => [
                    money_parse('30', 'RUB'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedAvg' => MoneyHasDifferentCurrenciesException::class,
            ],
            [
                'args' => [
                    null,
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedAvg' => '20',
            ],
            [
                'args' => [
                    collect(),
                ],
                'expectedAvg' => null,
            ],
            [
                'args' => [],
                'expectedAvg' => null,
            ],
        ];
    }

    /** @dataProvider providerSum */
    public function testSum(array $args, string|null $expectedSum): void
    {
        if ($this->isExceptionClass($expectedSum)) {
            $this->expectException($expectedSum);
        }

        $sum = Money::sum(...$args);

        if ($this->isExceptionClass($expectedSum)) {
            return;
        }

        if (is_null($expectedSum)) {
            $this->assertNull($sum);
            return;
        }

        $this->assertMoneyEquals(money_parse($expectedSum), $sum);
    }

    public function providerSum(): array
    {
        $this->createApplication();

        return [
            [
                'args' => [
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedSum' => '60',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                ],
                'expectedSum' => '60',
            ],
            [
                'args' => [
                    collect([
                        money_parse('30'),
                        money_parse('10'),
                        money_parse('20'),
                    ]),
                    money_parse('40'),
                ],
                'expectedSum' => '100',
            ],
            [
                'args' => [
                    money_parse('30', 'RUB'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedSum' => MoneyHasDifferentCurrenciesException::class,
            ],
            [
                'args' => [
                    null,
                    money_parse('30'),
                    money_parse('10'),
                    money_parse('20'),
                ],
                'expectedSum' => '60',
            ],
            [
                'args' => [
                    collect(),
                ],
                'expectedSum' => null,
            ],
            [
                'args' => [],
                'expectedSum' => null,
            ],
        ];
    }
}
