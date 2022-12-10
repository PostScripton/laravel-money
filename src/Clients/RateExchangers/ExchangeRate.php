<?php

namespace PostScripton\Money\Clients\RateExchangers;

use Carbon\Carbon;
use GuzzleHttp\RequestOptions;

class ExchangeRate extends AbstractRateExchanger
{
    public function __construct()
    {
        // Should override constructor in order to pass nothing to AbstractClient
        parent::__construct();
    }

    protected function baseUri(): string
    {
        return 'https://api.exchangerate.host/';
    }

    protected function getRateRequestPath(?Carbon $date = null): string
    {
        return is_null($date) ? 'latest' : $date->format('Y-m-d');
    }

    protected function getRateRequestOptions(string $from, array|string $to): array
    {
        return [
            RequestOptions::QUERY => [
                'base' => $from,
                'symbols' => collect((array) $to)
                    ->map(fn(string $to) => $to)
                    ->join(','),
            ],
        ];
    }

    protected function getRateFromResponse(array $response, string|array $to): float|array
    {
        $rates = $response['result']['rates'];

        return is_string($to) ? $rates[$to] : $rates;
    }

    protected function getSupportsRequestPath(): string
    {
        return 'symbols';
    }

    protected function getSupportedCodesFromResponse(array $response): array
    {
        return array_keys(data_get($response, 'result.symbols', []));
    }

    protected function isErrorInResponse(array $response): bool
    {
        return ! data_get($response, 'result.success', true);
    }
}
