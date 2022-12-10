<?php

namespace PostScripton\Money\Cache;

use Carbon\Carbon;
use PostScripton\Money\Clients\RateExchangers\RateExchanger;
use PostScripton\Money\Currency;

class RateExchangerCache extends MoneyCache implements RateExchanger
{
    public const RATE_EXCHANGER_SUPPORTS_KEY = 'rate-exchanger.supports';

    private const RATE_EXCHANGER_RATE_KEY_FORMAT = 'rate-exchanger.rate.%s-%s.%s';

    public function supports(array $codes): array
    {
        $fn = fn() => app(RateExchanger::class)->supports($codes);

        if ($this->isDisabled()) {
            return $fn();
        }

        $ttl = static::getTtlFromConfig('money.cache.rate_exchanger.supports.ttl');

        return $this->getRepository()->remember(static::RATE_EXCHANGER_SUPPORTS_KEY, $ttl, $fn);
    }

    public function rate(Currency|string $from, Currency|string|array $to, ?Carbon $date = null): float|array
    {
        $fn = fn() => app(RateExchanger::class)->rate($from, $to, $date);

        if ($this->isDisabled()) {
            return $fn();
        }

        $key = static::getRateCacheKey($from, $to, $date);
        $ttl = static::getTtlFromConfig('money.cache.rate_exchanger.rate.ttl', forever: ! is_null($date));

        $store = $this->getRepository();
        if ($this->supportsTags()) { // TODO: test in example project
            /** @var \Illuminate\Contracts\Cache\Repository $store */
            $store = $store->tags('money.rates');
        }

        return $store->remember($key, $ttl, $fn);
    }

    public function clear(): void
    {
        if ($this->supportsTags()) {
            $this->getRepository()->tags('money.rates')->flush();
        }

        $this->getRepository()->forget(self::RATE_EXCHANGER_SUPPORTS_KEY);
    }

    public static function getRateCacheKey(
        Currency|string $from,
        Currency|string|array $to,
        ?Carbon $date = null,
    ): string {
        return sprintf(
            static::RATE_EXCHANGER_RATE_KEY_FORMAT,
            Currency::get($from)->getCode(),
            collect(is_array($to) ? $to : [$to])
                ->map(fn(Currency|string $code) => Currency::get($code)->getCode())
                ->sort()
                ->join(','),
            ($date ?? now())->format('Y-m-d'),
        );
    }
}
