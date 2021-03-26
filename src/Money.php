<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;
use PostScripton\Money\Traits\MoneyFormatter;

class Money implements MoneyInterface
{
	use MoneyFormatter;

	private static $default_currency;
	private static $thousands_separator = ' ';
	private static $decimal_separator = '.';
	private static $decimals = 1;
	private static $ends_with_0 = false;

	/**
	 * @param string $thousands_separator
	 * @param string $decimal_separator
	 * @param int $decimals
	 * @param bool $ends_with_0
	 * @param Currency|null $default_currency
	 * @throws CurrencyDoesNotExistException|ShouldPublishConfigFileException
	 */
	public static function set(string $thousands_separator, string $decimal_separator, int $decimals, bool $ends_with_0 = false, ?Currency $default_currency = null): void
	{
		self::$thousands_separator = $thousands_separator;
		self::$decimal_separator = $decimal_separator;
		self::$decimals = $decimals;
		self::$ends_with_0 = $ends_with_0;
		self::$default_currency = $default_currency ?? Currency::code(Currency::getConfigCurrency());
	}

	/** Получить десятичный делитель
	 * @return int
	 */
	public static function getDivisor(): int
	{
		return 10 ** self::$decimals;
	}

	/** Возвращает валюту по умолчанию
	 * @return Currency
	 * @throws CurrencyDoesNotExistException|ShouldPublishConfigFileException
	 */
	public static function getDefaultCurrency(): Currency
	{
		return self::$default_currency ?? Currency::code(Currency::getConfigCurrency());
	}

	/** Проверяет, был ли опубликован конфиг
	 * @return bool
	 */
	public static function configNotPublished(): bool
	{
		return is_null(config('money'));
	}
}