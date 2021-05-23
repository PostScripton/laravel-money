<?php

namespace PostScripton\Money\Services;

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