<?php

namespace PostScripton\Money\Traits;

use Carbon\Carbon;
use PostScripton\Money\Cache\RateExchangerCache;
use PostScripton\Money\Currencies;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrenciesNotSupportedByRateExchangerException;
use PostScripton\Money\Exceptions\RateExchangerException;
use PostScripton\Money\Money;

trait InteractsWithRateExchanger
{
    public function convertTo(Currency|string $to, ?Carbon $date = null): Money
    {
        $from = $this->getCurrency();
        $to = Currency::get($to);

        if (Currencies::same($from, $to)) {
            return $this->clone();
        }

        $notSupported = array_intersect(app(RateExchangerCache::class)->supports(Currencies::getCodesArray()), [
            $from->getCode(),
            $to->getCode(),
        ]);
        if (! empty($notSupported)) {
            throw new CurrenciesNotSupportedByRateExchangerException($notSupported);
        }

        if ($date?->isFuture()) {
            throw new RateExchangerException('Cannot get exchange rate from the future');
        }
        $rate = app(RateExchangerCache::class)->rate($from, $to, $date);

        return $this->offlineConvertTo($to, $rate);
    }

    public function offlineConvertTo(Currency|string $currency, string $rate): Money
    {
        return money($this->amount, $currency)->multiply($rate);
    }
}
