<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Exceptions\NotNumericException;

interface MoneyInterface
{
    // ========== STATIC ========== //

    /**
     * Sets default settings for any Money object
     * @param MoneySettings $setting
     */
    public static function set(MoneySettings $setting): void;

    /**
     * Corrects input &lt;input type="number" /&gt; using default settings
     * @param string $input <p>
     * Input string: "1234.567890" </p>
     * @return string <p>
     * Corrected string: "1234.5" </p>
     */
    public static function correctInput(string $input): string;

    /**
     * Returns the default divisor
     * @return int
     */
    public static function getDefaultDivisor(): int;

    /**
     * Returns the default number of decimals
     * @return int
     */
    public static function getDefaultDecimals(): int;

    /**
     * Returns the default thousand separator
     * @return string
     */
    public static function getDefaultThousandsSeparator(): string;

    /**
     * Returns the default decimal separator
     * @return string
     */
    public static function getDefaultDecimalSeparator(): string;

    /**
     * Returns whether money ends with 0 or not
     * @return bool
     */
    public static function getDefaultEndsWith0(): bool;

    /**
     * Returns whether there is a space between currency sign and number
     * @return bool
     */
    public static function getDefaultSpaceBetween(): bool;

    /**
     * Returns the default currency
     * @return Currency
     */
    public static function getDefaultCurrency(): Currency;

    /**
     * Returns the default origin. Whether it is integer or float
     * @return int
     */
    public static function getDefaultOrigin(): int;

    // ========== OBJECT ========== //

    /**
     * Returns a formatted number <p>
     * For example, "$ 1 234.5" -> "1 234.5" </p>
     * @return string
     */
    public function getNumber(): string;

    /**
     * Returns a pure number that uses for calculations. Not usually used <p>
     * For example, you see "13.3" but within it looks like 13.276686139139672 </p>
     * @return float
     */
    public function getPureNumber(): float;

    /**
     * Shortcut for returning the currency <p>
     * Full: `$money->settings->getCurrency()` </p>
     * @return Currency
     */
    public function getCurrency(): Currency;

    /**
     * Adds a number to the money. It's like <p>
     * `$100 + $50 = $150` </p>
     * @param int|float|Money $money <p>
     * A number or Money that will be added </p>
     * @param int $origin <p>
     * Origin of the number whether it is integer of float. </p> <p>
     * Use `Money::ORIGIN_*` to ensure it's correct </p>
     * @throws MoneyHasDifferentCurrenciesException
     * @throws NotNumericException
     * @return Money
     */
    public function add($money, int $origin = MoneySettings::ORIGIN_INT): Money;

    /**
     * Subtracts a number from the money. It's like <p>
     * `$150 - $50 = $100` </p>
     * @param int|float|Money $money <p>
     * A number or Money that will be subtracted </p>
     * @param int $origin <p>
     * Origin of the number whether it is integer of float. </p> <p>
     * Use `Money::ORIGIN_*` to ensure it's correct </p>
     * @throws MoneyHasDifferentCurrenciesException
     * @throws NotNumericException
     * @return Money
     */
    public function subtract($money, int $origin = MoneySettings::ORIGIN_INT): Money;

    /**
     * Rebases the money on a number
     * @param int|float|Money $money <p>
     * A number or Money to which the money will be rebased </p>
     * @param int $origin <p>
     * Origin of the number whether it is integer of float. </p> <p>
     * Use `Money::ORIGIN_*` to ensure it's correct </p>
     * @throws MoneyHasDifferentCurrenciesException
     * @throws NotNumericException
     * @return Money
     */
    public function rebase($money, int $origin = MoneySettings::ORIGIN_INT): Money;

    /**
     * Converts money into another currency using coefficient between currencies
     * <p>USD -> RUB = 75.79 / 1</p>
     * <p>RUB -> USD = 1 / 75.79</p>
     * @param Currency $currency <p>
     * Currency you want to convert into </p>
     * @param float $coeff <p>
     * Coefficient between the money's currency and the chosen one
     * @return Money
     */
    public function convertOfflineInto(Currency $currency, float $coeff): Money;

    /**
     * Converts the money into integer for storing in database <p>
     * For example, "1 234.5" -> 12345 </p>
     * @return int
     */
    public function toInteger(): int;

    /**
     * Returns the money string applying all the settings <p>
     * You may not use it if you explicitly assign the object to a string </p>
     * @return string <p>
     * "$ 1 234.5" </p>
     */
    public function toString(): string;
}