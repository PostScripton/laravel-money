<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;

class Currency
{
	protected static $currencies = [];

	public const POS_START = 'start';
	public const POS_END = 'end';

	public static function code(string $code): ?Currency
	{
		$code = strtoupper($code);
		if (!array_key_exists($code, self::all())) {
			throw new CurrencyDoesNotExistException("The currency '{$code}' doesn't exist.");
		}

		$currency = self::all()[$code];

		return new Currency($code, $currency['symbol'], $currency['countries'], $currency['position']);
	}

	protected static function all(): array
	{
		if (!static::$currencies) {
			static::$currencies = require __DIR__ . '/List/currencies.php';
		}
		return static::$currencies;
	}

	// ==================== ОБЪЕКТ ==================== //

	private $code;
	private $symbol;
	private $countries;
	private $position;

	public function __construct(string $code, string $symbol, array $countries = [], ?string $position = null)
	{
		$this->code = $code;
		$this->symbol = $symbol;
		$this->countries = $countries;
		$this->position = $position ?? self::POS_START;
	}

	/**
	 * @throws ShouldPublishConfigFileException
	 */
	public static function getConfigCurrency()
	{
		if (Money::configNotPublished()) {
			throw new ShouldPublishConfigFileException();
		}

		return config('money.default_currency');
	}

	public function getCode(): string
	{
		return $this->code;
	}

	public function getSymbol(): string
	{
		return $this->symbol;
	}

	public function getCountries(): array
	{
		return $this->countries;
	}

	public function getPosition(): string
	{
		return $this->position;
	}

	public function setPosition(string $position = self::POS_START)
	{
		$this->position = $position;
	}
}