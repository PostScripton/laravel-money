<?php

namespace PostScripton\Money\Tests\Unit;

use Carbon\Carbon;
use Mockery;
use PostScripton\Money\Cache\RateExchangerCache;
use PostScripton\Money\Currencies;
use PostScripton\Money\Exceptions\CurrenciesNotSupportedByRateExchangerException;
use PostScripton\Money\Exceptions\RateExchangerException;
use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class ConvertCurrenciesTest extends TestCase
{
    use InteractsWithConfig;

    public function testConvertingToSameCurrencyReturnsClone(): void
    {
        $m1 = money_parse('100', 'USD');

        $m2 = $m1->convertTo('USD');

        $this->assertTrue($m1->equals($m2));
    }

    public function testConvertingThrowsExceptionAboutUnsupportedCurrencies(): void
    {
        $this->app->bind(RateExchangerCache::class, function () {
            return Mockery::mock(RateExchangerCache::class)
                ->shouldReceive('supports')
                ->andReturn(Currencies::getCodesArray())
                ->getMock();
        });
        $m1 = money_parse('100', 'USD');

        $this->expectException(CurrenciesNotSupportedByRateExchangerException::class);

        $m1->convertTo('GBP');
    }

    public function testConvertingThrowsExceptionBecauseUnableToGetHistoricalRateFromFuture(): void
    {
        $this->app->bind(RateExchangerCache::class, function () {
            return Mockery::mock(RateExchangerCache::class)
                ->shouldReceive('supports')
                ->andReturn([])
                ->getMock();
        });
        $m1 = money_parse('100', 'USD');

        $this->expectException(RateExchangerException::class);
        $this->expectExceptionMessage('Cannot get exchange rate from the future');

        $m1->convertTo('RUB', now()->addYear());
    }

    public function testConvertToOtherCurrency(): void
    {
        $this->app->bind(RateExchangerCache::class, function () {
            return Mockery::mock(RateExchangerCache::class)
                ->shouldReceive('supports')
                ->andReturn([])
                ->shouldReceive('rate')
                ->withArgs([currency('USD'), currency('RUB'), null])
                ->andReturn(71.3261)
                ->getMock();
        });
        $usd = money_parse('100', 'USD');
        $expectedMoney = money_parse('7132.61', 'RUB');

        $rub = $usd->convertTo('RUB');

        $this->assertTrue($expectedMoney->equals($rub));
    }

    public function testConvertToOtherCurrencyWithRateFromThePast(): void
    {
        $date = Carbon::parse('2021-12-28');
        $this->app->bind(RateExchangerCache::class, function () use ($date) {
            return Mockery::mock(RateExchangerCache::class)
                ->shouldReceive('supports')
                ->andReturn([])
                ->shouldReceive('rate')
                ->withArgs([currency('USD'), currency('RUB'), $date])
                ->andReturn(73.2329)
                ->getMock();
        });
        $usd = money_parse('100', 'USD');
        $expectedMoney = money_parse('7323.29', 'RUB');

        $rub = $usd->convertTo('RUB', $date);

        $this->assertTrue($expectedMoney->equals($rub));
    }

    public function testOfflineConvertingMayLooseAccuracy(): void
    {
        $rate = 75.32;
        $rub = money_parse('1000', 'RUB');
        $this->assertEquals('1 000 ₽', $rub->toString());

        $usd = $rub->offlineConvertTo('USD', 1 / $rate);
        $this->assertEquals('$ 13.3', $usd->toString());

        $backRub = $usd->offlineConvertTo('RUB', $rate / 1);
        $this->assertEquals('1 000 ₽', $backRub->toString());

        $this->assertFalse($rub->equals($backRub));
        $this->assertTrue($rub->isSameCurrency($backRub));
        $this->assertEquals('9999935', $backRub->getAmount());
    }
}
