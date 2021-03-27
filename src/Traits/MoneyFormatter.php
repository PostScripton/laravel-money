<?php

namespace PostScripton\Money\Traits;

use PostScripton\Money\Currency;

trait MoneyFormatter
{
	public static function format(int $amount, ?Currency $currency = null): string
	{
		$amount = (float)($amount / self::getDivisor());
		$money = number_format($amount, self::$decimals, self::$decimal_separator, self::$thousands_separator);

		if (!self::$ends_with_0) {
			# /^((\d+|\s*)*\.\d*[1-9]|(\d+|\s*)*)/ - берёт всё число, кроме 0 и .*0 на конце
			$pattern = '/^((\d+|' . self::$thousands_separator . '*)*\\' . self::$decimal_separator . '\d*[1-9]|(\d+|' . self::$thousands_separator . '*)*)/';
			preg_match($pattern, $money, $money);
			$money = $money[0];
		}

		return self::bindMoneyWithCurrency($money, $currency ?? self::getDefaultCurrency());
	}

	public static function convert(string $money, Currency $into, float $diff): string
	{
		$money = str_replace(self::$thousands_separator, '', $money);
		$money = str_replace(self::$decimal_separator, '.', $money);

		$pattern = '/\d+\.?\d+/';
		preg_match($pattern, $money, $money);
		$money = $money[0];

		return self::format(self::integer($money * $diff), $into);
	}

	public static function purify(string $money): string
	{
		$money = str_replace(self::$thousands_separator, '', $money);
		$money = str_replace(self::$decimal_separator, '.', $money);

		# /\d+\.?\d+/ - берёт только числа (целые и дробные)
		$pattern = '/\d+\.?\d+/';
		preg_match($pattern, $money, $money);

		return $money[0];
	}

	public static function integer(float $number): int
	{
		return floor(self::getDivisor() * $number);
	}

	public static function correctInput(string $input): string
	{
		if (!str_contains($input, '.')) return $input;

		return substr($input, 0, strpos($input, '.') + self::$decimals + 1);
	}

	/**
	 * Связывает денежную строку (простые отформатированые числа) с валютным знаком
	 * @param string $money <p>
	 * Денежная строка без валютного знака
	 * </p>
	 * @param Currency $currency <p>
	 * Валюта (константа)
	 * </p>
	 * @return string Денежная строка со знаком валюты
	 */
	private static function bindMoneyWithCurrency(string $money, Currency $currency): string
	{
		return $currency->getPosition() === Currency::POS_START
			? "{$currency->getSymbol()} {$money}"
			: "{$money} {$currency->getSymbol()}";
	}
}