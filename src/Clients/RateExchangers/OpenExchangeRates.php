<?php

namespace PostScripton\Money\Clients\RateExchangers;

use Carbon\Carbon;
use GuzzleHttp\RequestOptions;

class OpenExchangeRates extends AbstractRateExchanger
{
    public function __construct(protected readonly array $config)
    {
        parent::__construct([
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Token %s', data_get($config, 'key')),
            ],
        ]);
    }

    protected function baseUri(): string
    {
        return 'https://openexchangerates.org/api/';
    }

    protected function getRateRequestPath(?Carbon $date = null): string
    {
        return is_null($date) ? 'latest.json' : sprintf('historical/%s.json', $date->format('Y-m-d'));
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
        return 'currencies.json';
    }

    protected function getSupportedCodesFromResponse(array $response): array
    {
        return array_keys($response['result']);
    }

    protected function isErrorInResponse(array $response): bool
    {
        return $response['status'] !== 200;
    }
}
