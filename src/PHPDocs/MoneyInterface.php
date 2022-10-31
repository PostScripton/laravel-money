<?php

namespace PostScripton\Money\PHPDocs;

use Carbon\Carbon;
use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

interface MoneyInterface
{
    /**
     * Binds the money object with settings
     * @param MoneySettings $settings
     * @return self
     */
    public function bind(MoneySettings $settings): self;

    /**
     * Returns settings object
     * @return MoneySettings
     */
    public function settings(): MoneySettings;

    /**
     * Get currency of the monetary object
     * @return Currency
     */
    public function getCurrency(): Currency;

    /**
     * Set a new currency <p>
     * Note that changes only currency itself without converting </p>
     * For converting between currencies use `convertInto()` method
     * @param Currency $currency
     * @return self
     */
    public function setCurrency(Currency $currency): self;

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
     * Returns a pure number that uses for calculations. <p>
     * For example, you see "13.3" but within it looks like "132766" </p>
     * @return string
     */
    public function getPureAmount(): string;

    /**
     * Adds an amount to the money. It's like <p>
     * `$100 + $50 = $150` </p>
     * @param Money $money <p>
     * Money that will be added </p>
     * @throws \PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException
     * @return self
     */
    public function add(Money $money): self;

    /**
     * Subtracts an amount from the money. It's like <p>
     * `$150 - $50 = $100` </p>
     * @param Money $money <p>
     * Money that will be subtracted </p>
     * @throws \PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException
     * @return self
     */
    public function subtract(Money $money): self;

    /**
     * Multiplies an amount from the money. It's like <p>
     * `$100 * 2 = $200` </p>
     * @param float $number <p>
     * A number on which the money will be multiplied </p>
     * @return self
     */
    public function multiply(float $number): self;

    /**
     * Divides an amount from the money. It's like <p>
     * `$100 / 2 = $50` </p>
     * @param float $number <p>
     * A number on which the money will be divided </p>
     * @return self
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
    public function ceil(): self;

    /**
     * Turns the amount of the monetary object into an absolute value <p>
     * `$ -10.25 -> $ 10.25` </p>
     * @return self
     */
    public function absolute(): self;

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
     * Returns the money string applying all the settings <p>
     * You may not use it if you explicitly assign the object to a string </p>
     * @return string <p>
     * "$ 1 234.5" </p>
     */
    public function toString(): string;

    // ========== STATIC ========== //

    /**
     * Creates a monetary object
     * @param string $amount
     * @param null $currency
     * @param null $settings
     * @return self
     */
    public static function of(string $amount, $currency = null, $settings = null): Money;

    /**
     * Parses the string and turns it into a monetary instance
     * @param string $money
     * @param string|null $currencyCode
     * @return self
     */
    public static function parse(string $money, ?string $currencyCode = null): Money;

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
     * Returns the default a thousand separator
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
}
