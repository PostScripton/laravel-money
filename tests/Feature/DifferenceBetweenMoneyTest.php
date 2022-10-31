<?php

namespace PostScripton\Money\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Mockery;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Services\ExchangeRateService;
use PostScripton\Money\Services\ServiceInterface;
use PostScripton\Money\Tests\TestCase;

class DifferenceBetweenMoneyTest extends TestCase
{
    private $backupConfig;

    /** @test */
    public function differenceReturnsAString(): void
    {
        $m1 = money('500000');
        $m2 = money('1000000');

        $this->assertIsString($m1->difference($m2));
    }

    /** @test */
    public function differenceWithTwoSameCurrencies(): void
    {
        $m1 = money('500000');
        $m2 = money('1000000');

        $this->assertEquals(money('500000')->subtract($m2)->toString(), $m1->difference($m2));
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
            $usd->clone()->subtract($rubIntoUsd)->toString(),
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

    /** @test */
    public function newSettingsCanBeAppliedToTheDifference(): void
    {
        $m1 = money('500000');
        $m2 = money('1000000');

        $this->assertEquals(
            money('500000')->subtract($m2)->toString() . '.0',
            $m1->difference($m2, settings()->setEndsWith0(true))
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->backupConfig = Config::get('money');
        Config::set('money.service', 'exchangerate');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backupConfig);
    }
}
