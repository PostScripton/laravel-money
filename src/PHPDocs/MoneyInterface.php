<?php

namespace PostScripton\Money\PHPDocs;

use Carbon\Carbon;
use PostScripton\Money\Currency;
use PostScripton\Money\Formatters\MoneyFormatter;
use PostScripton\Money\Money;

interface MoneyInterface
{
    /**
     * Get currency of the monetary object
     * @return Currency
     */
    public function getCurrency(): Currency;

    /**
     * Set a new currency <p>
     * Note that changes only currency itself without converting </p>
     * For converting between currencies use `convertInto()` method
     * @param Currency|string $currency
     * @return self
     */
    public function setCurrency(Currency|string $currency): self;

    /**
     * Creates an absolutely identical instance of the object
     * @return Money
     */
    public function clone(): Money;

    /**
     * Returns a pure amount that is used for calculations. <p>
     * For example, you see "13.3" but within it looks like "132766" </p>
     * @return string
     */
    public function getAmount(): string;

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
     * @param string $multiplier <p>
     * A number on which the money will be multiplied </p>
     * @return self
     */
    public function multiply(string $multiplier): self;

    /**
     * Divides an amount from the money. It's like <p>
     * `$100 / 2 = $50` </p>
     * @param string $divisor <p>
     * A number on which the money will be divided </p>
     * @return self
     */
    public function divide(string $divisor): self;

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
     * Checks whether two monetary objects have the same currency
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
     * Checks whether two monetary objects are equal or not
     * @param Money $money
     * @param bool $strict <p>
     * Whether to check currencies or not
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
     * Shows the difference between two monetary objects <p>
     * $50 - $100 = "$ -50" </p> <p>
     * In fact, this method is an alias to `->clone()->subtract()->absolute()` </p>
     * @param Money $money <p>
     * The given money must be the same currency as the first one </p>
     * @throws \PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException
     * @return Money
     *
     */
    public function difference(Money $money): Money;

    /**
     * Allows you to get access to the selected service from the config file
     */
    public function service();

    /**
     * Returns a formatted string with the applied formatting settings from the config file <p>
     * You may not use it if you explicitly assign the object to a string </p>
     * @param MoneyFormatter|null $formatter <p>
     * You may apply your own formatter in order to represent a monetary object the way you want </p>
     * @return string <p>
     * "$ 1 234.5" </p>
     */
    public function toString(?MoneyFormatter $formatter = null): string;

    // ========== STATIC ========== //

    /**
     * Sets a default formatter that will be applied by default to all monetary objects
     * @param MoneyFormatter $formatter
     * @return void
     */
    public static function setFormatter(MoneyFormatter $formatter): void;

    /**
     * Sets a default currency that will be used for creating new monetary objects <p>
     * By default, is set by the value from the config file </p>
     * @param Currency $currency
     */
    public static function setDefaultCurrency(Currency $currency): void;

    /**
     * Creates a monetary object
     * @param string $amount <p>
     * Raw amount: 12345 stands for 1.2345 </p>
     * @param Currency|string|null $currency
     * @return self
     */
    public static function of(string $amount, Currency|string|null $currency = null): Money;

    /**
     * Parses the string and turns it into a monetary instance
     * @param string $money
     * @param Currency|string|null $currency
     * @return self
     */
    public static function parse(string $money, Currency|string|null $currency = null): Money;

    /**
     * Corrects input &lt;input type="number" /&gt; using default settings
     * @param string $input <p>
     * Input string: "1234.567890" </p>
     * @return string <p>
     * Corrected string: "1234.5" </p>
     */
    public static function correctInput(string $input): string;

    /**
     * Returns the default divisor (10^4)
     * @return int
     */
    public static function getDefaultDivisor(): int;

    /**
     * Returns the default currency that is set in the config file
     * @return Currency
     */
    public static function getDefaultCurrency(): Currency;
}
