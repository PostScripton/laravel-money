<?php

namespace PostScripton\Money\Services;

use Carbon\Carbon;
use PostScripton\Money\Exceptions\ServiceRequestFailedException;

class OpenExchangeRatesService extends AbstractService
{
    protected function domain(): string
    {
        return 'openexchangerates.org';
    }

    protected function uri(): string
    {
        return 'api';
    }

    protected function latestUri(): string
    {
        return 'latest.json';
    }

    protected function supportedUri(): string
    {
        return 'currencies.json';
    }

    protected function historicalUri(Carbon $date, array &$query): string
    {
        return 'historical/' . $date->format(self::DATE_FORMAT) . '.json';
    }

    protected function baseQuery(): array
    {
        return ['app_id' => $this->config['key']];
    }

    protected function supportedData(array $data, string $index): array
    {
        return $data;
    }

    protected function validateResponse(array $data): void
    {
        // Verify the server response
        if (array_key_exists('error', $data)) {
            throw new ServiceRequestFailedException($this->getClassName(), $data['status'], $data['description']);
        }
    }
}
