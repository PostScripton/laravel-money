<?php

namespace PostScripton\Money\Cache;

use Carbon\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;

class MoneyCache
{
    private Repository $cache;

    public function __construct(private readonly CacheManager $cacheManager)
    {
        $this->cache = $this->getCacheStoreFromConfig();
    }

    public function getRepository(): Repository
    {
        return $this->cache;
    }

    public function getStore(): Store
    {
        return $this->cache->getStore();
    }

    public function supportsTags(): bool
    {
        return method_exists($this->getStore(), 'tags');
    }

    protected function isDisabled(): bool
    {
        return ! config('money.cache.enabled', false);
    }

    protected static function getTtlFromConfig(string $key, bool $forever = false): ?Carbon
    {
        if ($forever) {
            return null;
        }

        $value = config($key);
        if (is_null($value)) {
            return null;
        }

        return now()->add($value);
    }

    private function getCacheStoreFromConfig(): Repository
    {
        $cacheDriver = config('money.cache.store', 'default');

        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        if (! array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }
}
