<?php

namespace PostScripton\Money\Clients\RateExchangers;

use Carbon\Carbon;
use PostScripton\Money\Currency;

interface RateExchanger
{
    /**
     * Check whether an API rate exchanger supports provided currency codes or doesn't
     * @param array<Currency|string> $codes
     * Codes to check
     * @return array
     * UNSUPPORTED codes from the initial list of codes
     */
    public function supports(array $codes): array;

    /**
     * Get an exchange rate(s) from API rate exchanger
     * @param Currency|string $from
     * A base currency. In ISO-code format
     * @param Currency|string|array $to
     * Exchange rate for a single currency or a several ones. In ISO-code format
     * @param Carbon|null $date
     * @return float|array
     * A single exchange rate or an array of [code => rate] type
     */
    public function rate(Currency|string $from, Currency|string|array $to, ?Carbon $date = null): float|array;
}
