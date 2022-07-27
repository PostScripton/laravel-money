<?php

namespace PostScripton\Money\PHPDocs;

use PostScripton\Money\MoneySettings;

interface MoneySettingsInterface
{
    /**
     * Sets decimals <p>
     * 123.(4) </p>
     * @param int $decimals
     * @return MoneySettings
     */
    public function setDecimals(int $decimals = MoneySettings::MIN_DECIMALS): MoneySettings;

    /**
     * Sets a thousand separator <p>
     * 1( )000( )000 </p>
     * @param string $separator
     * @return MoneySettings
     */
    public function setThousandsSeparator(string $separator): MoneySettings;

    /**
     * Sets a decimal separator <p>
     * 123(.)4</p>
     * @param string $separator
     * @return MoneySettings
     */
    public function setDecimalSeparator(string $separator): MoneySettings;

    /**
     * Sets whether a number ends with 0 or not
     * <p>true: 100(.0) </p>
     * <p>false: 100() </p>
     * @param bool $ends
     * @return MoneySettings
     */
    public function setEndsWith0(bool $ends = false): MoneySettings;

    /**
     * Sets whether there is a space between currency sign and number
     * <p>true: "$ 123.4" </p>
     * <p>false: "$123.4" </p>
     * @param bool $space
     * @return MoneySettings
     */
    public function setHasSpaceBetween(bool $space = true): MoneySettings;

    /**
     * Returns the decimals
     * @return int
     */
    public function getDecimals(): int;

    /**
     * Returns the thousand separator
     * @return string
     */
    public function getThousandsSeparator(): string;

    /**
     * Returns the decimal separator
     * @return string
     */
    public function getDecimalSeparator(): string;

    /**
     * Returns whether the money ends with 0 or not
     * @return bool
     */
    public function endsWith0(): bool;

    /** Returns whether there is a space between currency and number or not
     * @return bool
     */
    public function hasSpaceBetween(): bool;
}
