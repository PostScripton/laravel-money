<?php

namespace PostScripton\Money\Services;

use Carbon\Carbon;
use PostScripton\Money\Exceptions\ServiceRequestFailedException;

class ExchangeRateService extends AbstractService
{
    protected function domain(): string
    {
        return 'api.exchangerate.host';
    }

    protected function supportedUri(): string
    {
        return 'symbols';
    }

    protected function latestUri(): string
    {
        return 'latest';
    }

    protected function historicalUri(Carbon $date, array &$query): string
    {
        return $date->format(self::DATE_FORMAT);
    }

    protected function baseQuery(): array
    {
        return [];
    }

    protected function validateResponse(array $data): void
    {
        // Verify the server response
        if (array_key_exists('error', $data)) {
            throw new ServiceRequestFailedException(
                $this->getClassName(),
                $data['error']['code'],
                $data['error']['info']
            );
        }
    }
}
