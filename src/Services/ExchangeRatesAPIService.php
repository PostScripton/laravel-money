<?php

namespace PostScripton\Money\Services;

use Illuminate\Support\Carbon;
use PostScripton\Money\Exceptions\ServiceRequestFailedException;

class ExchangeRatesAPIService extends AbstractService
{
	protected function domain(): string
	{
		return 'api.exchangeratesapi.io';
	}

	protected function uri(): string
	{
		return 'v1';
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

	protected function validateResponse(array $data): void
	{
		// Verify the server response
		if (array_key_exists('error', $data)) {
			throw new ServiceRequestFailedException($this->getClassName(), $data['error']['code'], $data['error']['info']);
		}
	}
}