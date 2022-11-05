<?php

namespace PostScripton\Money\Tests\Feature;

use Mockery;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Money;
use PostScripton\Money\Services\ExchangeRateService;
use PostScripton\Money\Services\ServiceInterface;
use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class DifferenceBetweenMoneyTest extends TestCase
{
    use InteractsWithConfig;

    /**
     * @test
     * @dataProvider differenceDataProvider
     */
    public function difference(string $first, string $second, string $result): void
    {
        $m1 = money_parse($first);
        $m2 = money_parse($second);

        $diff = $m1->difference($m2);

        $this->assertInstanceOf(Money::class, $diff);
        $this->assertEquals($result, $diff->toString());
    }

    /** @test */
    public function differenceWithTwoDifferentCurrencies(): void
    {
        $this->app->bind(ServiceInterface::class, function () {
            return Mockery::mock(ExchangeRateService::class)
                ->makePartial()
                ->shouldReceive('supports')
                ->with(['USD', 'RUB'])
                ->andReturn([])
                ->shouldReceive('rate')
                ->with('RUB', 'USD', null)
                ->andReturn(1 / 65)
                ->getMock();
        });
        $usd = money('500000');
        $rub = money('1000000', currency('rub'));
        $rubIntoUsd = $rub->convertInto($usd->getCurrency());

        $this->assertEquals(
            $usd->clone()->subtract($rubIntoUsd),
            $usd->difference($rubIntoUsd),
        );
    }

    /** @test */
    public function anExceptionIsThrownWhenThereAreTwoDifferentCurrencies(): void
    {
        $m1 = money('500000');
        $m2 = money('1000000', currency('rub'));

        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1->difference($m2);
    }

    protected function differenceDataProvider(): array
    {
        return [
            [
                'first' => '$ 25',
                'second' => '$ 100',
                'result' => '$ 75',
            ],
            [
                'first' => '$ 100',
                'second' => '$ 25',
                'result' => '$ 75',
            ],
            [
                'first' => '$ 0',
                'second' => '$ 25',
                'result' => '$ 25',
            ],
            [
                'first' => '$ 25',
                'second' => '$ 0',
                'result' => '$ 25',
            ],
            [
                'first' => '$ 0',
                'second' => '$ 0',
                'result' => '$ 0',
            ],
            [
                'first' => '$ 100',
                'second' => '$ -25',
                'result' => '$ 125',
            ],
            [
                'first' => '$ -100',
                'second' => '$ -115',
                'result' => '$ 15',
            ],
            [
                'first' => '$ -500',
                'second' => '$ 100',
                'result' => '$ 600',
            ],
        ];
    }
}
