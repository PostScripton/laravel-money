<?php

namespace PostScripton\Money;

interface MoneyInterface
{
    // ========== STATIC ========== //

    /** Устанавливает значения
     * @param MoneySettings $setting
     */
    public static function set(MoneySettings $setting): void;

    /** Возвращает чистое денежное число <p>
     * К примеру, "1 234.56 ₽" -> "1234.56" </p>
     * @param Money $money <p>
     * Денежная строка: "1 234.56 ₽" </p>
     * @return string <p>
     * Чистое денежное число: "1234.56" </p>
     */
    public static function purify(Money $money): string;

    /** Возвращает целое денежное число из десятичного
     * @param Money $money <p>
     * Денежное число: 1234.567 (установлено 2 дес. знака) </p>
     * @return int <p>
     * Чистое целое денежное число: 123456 </p>
     */
    public static function integer(Money $money): int;

    /**
     * Конвертирует денежную строку из одной валюты в другую
     * @param Money $money <p>
     * Денежная строка, полученная методом format()
     * </p>
     * @param Currency $into <p>
     * Получившаяся фалюта (константа)
     * </p>
     * @param float $coeff
     * <p>Разница между конвертируемой валютой и получившейся.</p>
     * <p>USD -> RUB = 75.79 / 1</p>
     * <p>RUB -> USD = 1 / 75.79</p>
     * @return Money Денежная строка со знаком валюты
     */
    public static function convertOffline(Money $money, Currency $into, float $coeff): Money;

    /** Корректирует ввод &lt;input type="number" /&gt; под правильные значения
     * @param string $input <p>
     * Строка, полученная из input: "1234.567890" </p>
     * @return string <p>
     * Откорректированная строка: "1234.5" </p>
     */
    public static function correctInput(string $input): string;

    // todo отдельным коммитом дописать переводы на английском
    public static function getDefaultDivisor(): int;

    public static function getDefaultDecimals(): int;

    public static function getDefaultThousandsSeparator(): string;

    public static function getDefaultDecimalSeparator(): string;

    public static function getDefaultEndsWith0(): bool;

    public static function getDefaultSpaceBetween(): bool;

    public static function getDefaultCurrency(): Currency;

    // ========== OBJECT ========== //

    public function getNumber(): string;

    public function getPureNumber(): float;

    public function convertOfflineInto(Currency $currency, float $coeff): Money;

    public function toInteger(): int;

    public function toString(): string;
}