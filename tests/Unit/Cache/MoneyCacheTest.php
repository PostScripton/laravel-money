<?php

namespace PostScripton\Money\Tests\Unit\Cache;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\DatabaseStore;
use Illuminate\Cache\RedisStore;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\Cache\RateExchangerCache;
use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class MoneyCacheTest extends TestCase
{
    use InteractsWithConfig;

    public function testGetDefaultStore(): void
    {
        Config::set('cache.default', 'redis');
        Config::set('money.cache.store', 'default');

        $this->assertInstanceOf(RedisStore::class, app(RateExchangerCache::class)->getStore());
    }

    public function testGetDifferentStoreApartFromDefault(): void
    {
        Config::set('cache.default', 'redis');
        Config::set('money.cache.store', 'database');

        $this->assertInstanceOf(DatabaseStore::class, app(RateExchangerCache::class)->getStore());
    }

    public function testUnknownStoreResolvesToArrayStore(): void
    {
        Config::set('cache.default', 'redis');
        Config::set('money.cache.store', 'qwerty');

        $this->assertInstanceOf(ArrayStore::class, app(RateExchangerCache::class)->getStore());
    }

    public function testStoreThatSupportsTags(): void
    {
        Config::set('money.cache.store', 'array');

        $this->assertTrue(app(RateExchangerCache::class)->supportsTags());
    }

    public function testStoreThatDoesNotSupportTags(): void
    {
        Config::set('money.cache.store', 'file');

        $this->assertFalse(app(RateExchangerCache::class)->supportsTags());
    }
}
