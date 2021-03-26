<?php

namespace PostScripton\Money;

interface MoneyInterface
{
	/** Устанавливает значения
	 * @param string $thousands_separator <p>
	 * Тысячный разделитель </p>
	 * @param string $decimal_separator <p>
	 * Десятичный разделитель </p>
	 * @param int $decimals <p>
	 * Кол-во десятичных знаков </p>
	 * @param bool $ends_with_0 <p>
	 * Заканчивается на 0 или нет </p>
	 * @param Currency|null $default_currency <p>
	 * Валюта по умолчанию </p>
	 */
	public static function set(string $thousands_separator, string $decimal_separator, int $decimals, bool $ends_with_0 = false, ?Currency $default_currency = null): void;

	/**
	 * Форматирует число в денежную строку
	 * @param int $amount <p>
	 * Форматируемое число *обязательно целое* (будущая сумма денег)
	 * </p>
	 * @param Currency|null $currency
	 * @return string Денежная строка со знаком валюты
	 */
	public static function format(int $amount, ?Currency $currency = null): string;

	/** Возвращает чистое денежное число <p>
	 * К примеру, "1 234.56 ₽" -> "1234.56" </p>
	 * @param string $money <p>
	 * Денежная строка: "1 234.56 ₽" </p>
	 * @return string <p>
	 * Чистое денежное число: "1234.56" </p>
	 */
	public static function purify(string $money): string;

	/** Возвращает целое денежное число из десятичного
	 * @param float $number <p>
	 * Денежное число: 1234.567 (установлено 2 дес. знака) </p>
	 * @return int <p>
	 * Чистое целое денежное число: 123456 </p>
	 */
	public static function integer(float $number): int;

	/**
	 * Конвертирует денежную строку из одной валюты в другую
	 * @param string $money <p>
	 * Денежная строка, полученная методом format()
	 * </p>
	 * @param Currency $into <p>
	 * Получившаяся фалюта (константа)
	 * </p>
	 * @param float $diff
	 * <p>Разница между конвертируемой валютой и получившейся.</p>
	 * <p>USD -> RUB = 75.79 / 1</p>
	 * <p>RUB -> USD = 1 / 75.79</p>
	 * @return string Денежная строка со знаком валюты
	 */
	public static function convert(string $money, Currency $into, float $diff): string;

	/** Корректирует ввод &lt;input type="number" /&gt; под правильные значения
	 * @param string $input <p>
	 * Строка, полученная из input: "1234.567890" </p>
	 * @return string <p>
	 * Откорректированная строка: "1234.5" </p>
	 */
	public static function correctInput(string $input): string;
}