<?php

namespace PostScripton\Money\Services;

use Carbon\Carbon;

interface ServiceInterface
{
    /**
     * Currency exchange rate
     * @param string $from
     * @param string $to
     * @param Carbon|null $date
     * @return float
     */
    public function rate(string $from, string $to, ?Carbon $date = null): float;

    /**
     * Whether the service supports currencies or not
     * @param string|string[] $isos <p>
     * ISO-codes: "USD", "RUB", etc.
     * </p>
     * @return array <p>
     * NOT supported currencies
     * </p>
     */
    public function supports($isos): array;

    /**
     * Gives a full service class name with namespace
     * @return string
     */
    public function getClassName(): string;

    /**
     * A base url for API requests
     * @return string
     */
    public function url(): string;
}
