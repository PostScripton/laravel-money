<?php

namespace PostScripton\Money\PHPDocs;

use Carbon\Carbon;
use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

interface MoneyInterface
{
    // ========== STATIC ========== //

    /**
     * Creates a Money object
     * @param float $amount
     * @param null $currency
     * @param null $settings
     * @return self
     */
    public static function make(float $amount, $currency = null, $settings = null): self;

    /**
     * Parses the string and turns it into a money instance
     * @param string $money
     * @return self
     */
    public static function parse(string $money): self;

    /**
     * Binds the money object with settings
     * @param MoneySettings $settings
     * @return Money
     */
    public function bind(MoneySettings $settings): Money;

    /**
     * Returns settings object
     * @return MoneySettings
     */
    public function settings(): MoneySettings;

    /**
     * Creates an absolutely identical instance of the object
     * @return Money
     */
    public function clone(): Money;

    /**
     * Returns a formatted number <p>
     * For example, "$ 1 234.5" -> "1 234.5" </p>
     * @return string
     */
    public function getAmount(): string;

    /**
     * Returns a pure number that uses for calculations. Not usually used <p>
     * For example, you see "13.3" but within it looks like 13.276686139139672 </p>
     * @return float
     */
    public function getPureAmount(): float;

    /**
     * Shortcut for getting the currency <p>
     * Full: `$money->settings()->getCurrency()` </p>
     * @return Currency
     */
    public function getCurrency(): Currency;

    /**
     * Adds an amount to the money. It's like <p>
     * `$100 + $50 = $150` </p>
     * @param Money $money <p>
     * Money that will be added </p>
     * @throws \PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException
     * @return Money
     */
    public function add(Money $money): self;

    /**
     * Subtracts an amount from the money. It's like <p>
     * `$150 - $50 = $100` </p>
     * @param Money $money <p>
     * Money that will be subtracted </p>
     * @throws \PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException
     * @return Money
     */
    public function subtract(Money $money): self;

    /**
     * Multiplies an amount from the money. It's like <p>
     * `$100 * 2 = $200` </p>
     * @param float $number <p>
     * A number on which the money will be multiplied </p>
     * @return Money
     */
    public function multiply(float $number): self;

    /**
     * Divides an amount from the money. It's like <p>
     * `$100 / 2 = $50` </p>
     * @param float $number <p>
     * A number on which the money will be divided </p>
     * @return Money
     */
    public function divide(float $number): self;

    /**
     * Rebases the money on an amount
     * @param Money $money <p>
     * A monetary object to which the current money will be rebased </p>
     * @throws \PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException
     * @return self
     */
    public function rebase(Money $money): self;

    /**
     * Round fractions down <p>
     * `$10.25 -> $10.00` </p>
     * @return self
     */
    public function floor(): self;

    /**
     * Round fractions up <p>
     * `$10.25 -> $11.00` </p>
     * @return self
     */
    public function ceil(): Money;

    /**
     * Checks whether two money objects have the same currency
     * @param Money $money
     * @return bool
     */
    public function isSameCurrency(Money $money): bool;

    /**
     * Checks whether the money's number is negative (less than zero)
     * @return bool
     */
    public function isNegative(): bool;

    /**
     * Checks whether the money's number is positive (greater than zero)
     * @return bool
     */
    public function isPositive(): bool;

    /**
     * Checks whether the money's number is zero
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Is the current monetary object less than the given one? <p>
     * NOTE: monetary objects must be the same currency. </p>
     * @param Money $money <p>
     * Money being compared </p>
     * @return bool
     */
    public function lessThan(Money $money): bool;

    /**
     * Is the current monetary object less than or equal to the given one? <p>
     * NOTE: monetary objects must be the same currency. </p>
     * @param Money $money <p>
     * Money being compared </p>
     * @return bool
     */
    public function lessThanOrEqual(Money $money): bool;

    /**
     * Is the current monetary object greater than the given one? <p>
     * NOTE: monetary objects must be the same currency. </p>
     * @param Money $money <p>
     * Money being compared </p>
     * @return bool
     */
    public function greaterThan(Money $money): bool;

    /**
     * Is the current monetary object greater than or equal to the given one? <p>
     * NOTE: monetary objects must be the same currency. </p>
     * @param Money $money <p>
     * Money being compared </p>
     * @return bool
     */
    public function greaterThanOrEqual(Money $money): bool;

    /**
     * Checks whether two money objects are equal or not
     * @param Money $money
     * @param bool $strict <p>
     * Whether it is === or ==
     * @return bool
     */
    public function equals(Money $money, bool $strict = true): bool;

    /**
     * Converts money into another currency using an exchange rate between currencies
     * <p>USD -> RUB = 75.79 / 1</p>
     * <p>RUB -> USD = 1 / 75.79</p> <br/> <p>
     * You can do it whether online or offline by not passing or passing the $rate parameter
     * </p>
     * @param Currency $currency <p>
     * Currency you want to convert into </p>
     * @param float|null $rate <p>
     * Rate of the money's currency and the chosen one </p>
     * @param Carbon|null $date <p>
     * Historical mode. Pass the date you want to get rate of
     * </p>
     * @return self
     */
    public function convertInto(Currency $currency, ?float $rate = null, ?Carbon $date = null): Money;

    /**
     * Shows the difference between two money objects <p>
     * $50 - $100 = "$ -50" </p>
     * @param Money $money <p>
     * The given money must be the same currency as the first one </p>
     * @param MoneySettings|null $settings <p>
     * Settings for displaying the difference </p>
     * @return string
     */
    public function difference(Money $money, ?MoneySettings $settings = null): string;

    /**
     * Allows you to get access to the selected service from the config file
     */
    public function service();

    /**
     * Converts the money into the number according to origin for storing in database <p>
     * For example, "1 234.5" -> 12345, origin INT </p>
     * @return int|float
     */
    public function upload();

    /**
     * Returns the money string applying all the settings <p>
     * You may not use it if you explicitly assign the object to a string </p>
     * @return string <p>
     * "$ 1 234.5" </p>
     */
    public function toString(): string;

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
}
