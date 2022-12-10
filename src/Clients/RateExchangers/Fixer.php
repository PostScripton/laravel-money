<?php

namespace PostScripton\Money\Clients\RateExchangers;

use Carbon\Carbon;
use GuzzleHttp\RequestOptions;

class Fixer extends AbstractRateExchanger
{
    public function __construct(protected readonly array $config)
    {
        parent::__construct([
            RequestOptions::QUERY => [
                'access_key' => data_get($config, 'key'),
            ],
        ]);
    }

    protected function baseUri(): string
    {
        return $this->protocol() . '://data.fixer.io/api/';
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
        return array_keys($response['result']['symbols']);
    }

    protected function isErrorInResponse(array $response): bool
    {
        return ! data_get($response, 'result.success', true);
    }

    private function protocol(): string
    {
        if (! array_key_exists('free_plan', $this->config)) {
            return 'https';
        }

        return $this->config['free_plan'] ? 'http' : 'https';
    }
}
