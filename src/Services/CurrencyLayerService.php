<?php

namespace PostScripton\Money\Services;

use Carbon\Carbon;
use PostScripton\Money\Exceptions\ServiceRequestFailedException;

class CurrencyLayerService extends AbstractService
{
	protected string $currencies = 'currencies';
	protected string $base = 'source';
	protected string $result = 'quotes';

	protected function domain(): string
	{
		return 'api.currencylayer.com';
	}

	protected function supportedUri(): string
	{
		return 'list';
	}

	protected function latestUri(): string
	{
		return 'live';
	}

	protected function historicalUri(Carbon $date, array &$query): string
	{
		$query = array_merge($query, [
			'date' => $date->format(self::DATE_FORMAT)
		]);

		return 'historical';
	}

	protected static function BASE_CURRENCY(): string
	{
		return 'USD';
	}

	protected function validateResponse(array $data): void
	{
		// Verify the server response
		if (!$data['success']) {
			throw new ServiceRequestFailedException($this->getClassName(), $data['error']['code'], $data['error']['info']);
		}
	}
}