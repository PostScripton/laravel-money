<?php

namespace PostScripton\Money\Tests\Unit\Cache;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Mockery;
use PostScripton\Money\Cache\RateExchangerCache;
use PostScripton\Money\Clients\RateExchangers\RateExchanger;
use PostScripton\Money\Currencies;
use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class RateExchangerCacheTest extends TestCase
{
    use InteractsWithConfig;

    public function testGetRateWithoutCaching(): void
    {
        Config::set('money.cache.enabled', false);
        $this->app->bind(RateExchanger::class, function () {
            return Mockery::mock(RateExchanger::class)
                ->shouldReceive('rate')
                ->withArgs(['USD', 'RUB', null])
                ->andReturnUsing(fn() => mt_rand() / mt_getrandmax())
                ->getMock();
        });

        $this->assertNotEquals(
            app(RateExchangerCache::class)->rate('USD', 'RUB'),
            app(RateExchangerCache::class)->rate('USD', 'RUB'),
        );
        $this->assertFalse(
            app(RateExchangerCache::class)->getRepository()->has(
                RateExchangerCache::getRateCacheKey('USD', 'RUB', now()),
            ),
        );
    }

    public function testGetRateAndCacheIt(): void
    {
        Config::set('money.cache.enabled', true);
        $this->app->bind(RateExchanger::class, function () {
            return Mockery::mock(RateExchanger::class)
                ->shouldReceive('rate')
                ->withArgs(['USD', 'RUB', null])
                ->andReturnUsing(fn() => mt_rand() / mt_getrandmax())
                ->getMock();
        });

        $this->assertEquals(
            app(RateExchangerCache::class)->rate('USD', 'RUB'),
            app(RateExchangerCache::class)->rate('USD', 'RUB'),
        );
        $this->assertTrue(
            app(RateExchangerCache::class)->getRepository()->tags('money.rates')->has(
                RateExchangerCache::getRateCacheKey('USD', 'RUB', now()),
            ),
        );
    }

    public function testGetHistoricalRateAndCacheIt(): void
    {
        Config::set('money.cache.enabled', true);
        $date = now()->subYear();
        $this->app->bind(RateExchanger::class, function () use ($date) {
            return Mockery::mock(RateExchanger::class)
                ->shouldReceive('rate')
                ->withArgs(['USD', 'RUB', $date])
                ->andReturnUsing(fn() => mt_rand() / mt_getrandmax())
                ->getMock();
        });

        $this->assertEquals(
            app(RateExchangerCache::class)->rate('USD', 'RUB', $date),
            app(RateExchangerCache::class)->rate('USD', 'RUB', $date),
        );
        $this->assertTrue(
            app(RateExchangerCache::class)->getRepository()->tags('money.rates')->has(
                RateExchangerCache::getRateCacheKey('USD', 'RUB', $date),
            ),
        );
    }

    public function testSupportsWithoutCaching(): void
    {
        Config::set('money.cache.enabled', false);
        $this->app->bind(RateExchanger::class, function () {
            return Mockery::mock(RateExchanger::class)
                ->shouldReceive('supports')
                ->withAnyArgs()
                ->andReturnUsing(fn() => [Str::random()])
                ->getMock();
        });

        $this->assertNotEquals(
            app(RateExchangerCache::class)->supports(Currencies::getCodesArray()),
            app(RateExchangerCache::class)->supports(Currencies::getCodesArray()),
        );
        $this->assertFalse(
            app(RateExchangerCache::class)->getRepository()->has(RateExchangerCache::RATE_EXCHANGER_SUPPORTS_KEY),
        );
    }

    public function testSupportsAndCacheIt(): void
    {
        Config::set([
            'money.cache.enabled' => true,
            'money.cache.rate_exchanger.supports.ttl' => null, // stores forever
        ]);
        $this->app->bind(RateExchanger::class, function () {
            return Mockery::mock(RateExchanger::class)
                ->shouldReceive('supports')
                ->withAnyArgs()
                ->andReturnUsing(fn() => [Str::random()])
                ->getMock();
        });

        $this->assertEquals(
            app(RateExchangerCache::class)->supports(Currencies::getCodesArray()),
            app(RateExchangerCache::class)->supports(Currencies::getCodesArray()),
        );
        $this->assertTrue(
            app(RateExchangerCache::class)->getRepository()->has(RateExchangerCache::RATE_EXCHANGER_SUPPORTS_KEY),
        );
    }

    public function testClear(): void
    {
        Config::set('money.cache.enabled', true);
        $this->app->bind(RateExchanger::class, function () {
            return Mockery::mock(RateExchanger::class)
                ->shouldReceive('supports')
                ->withAnyArgs()
                ->andReturnUsing(fn() => [Str::random()])
                ->shouldReceive('rate')
                ->withArgs(['USD', 'RUB', null])
                ->andReturnUsing(fn() => mt_rand() / mt_getrandmax())
                ->getMock();
        });
        app(RateExchangerCache::class)->supports(Currencies::getCodesArray());
        app(RateExchangerCache::class)->rate('USD', 'RUB');
        $this->assertTrue(
            app(RateExchangerCache::class)
                ->getRepository()
                ->has(RateExchangerCache::RATE_EXCHANGER_SUPPORTS_KEY)
        );
        $this->assertTrue(
            app(RateExchangerCache::class)
                ->getRepository()
                ->tags('money.rates')
                ->has(RateExchangerCache::getRateCacheKey('USD', 'RUB', now()))
        );

        app(RateExchangerCache::class)->clear();

        $this->assertFalse(
            app(RateExchangerCache::class)
                ->getRepository()
                ->has(RateExchangerCache::RATE_EXCHANGER_SUPPORTS_KEY)
        );
        $this->assertFalse(
            app(RateExchangerCache::class)
                ->getRepository()
                ->tags('money.rates')
                ->has(RateExchangerCache::getRateCacheKey('USD', 'RUB', now()))
        );
    }
}
