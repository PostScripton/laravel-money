<?php

namespace PostScripton\Money;

interface MoneySettingsInterface
{
    public function setDecimals(int $decimals = 1): MoneySettings;

    public function setThousandsSeparator(string $separator): MoneySettings;

    public function setDecimalSeparator(string $separator): MoneySettings;

    public function setEndsWith0(bool $ends = false): MoneySettings;

    /** Есть ли пробел между знаком валюты и числом
     * @param bool $space
     * @return MoneySettings
     */
    public function setHasSpaceBetween(bool $space = true): MoneySettings;

    public function setCurrency(Currency $currency): MoneySettings;

    public function setOrigin(int $origin): MoneySettings;

    public function getDecimals(): int;

    public function getThousandsSeparator(): string;

    public function getDecimalSeparator(): string;

    public function endsWith0(): bool;

    /** Есть ли пробел между знаком валюты и числом
     * @return bool
     */
    public function hasSpaceBetween(): bool;

    public function getCurrency(): Currency;

    public function getOrigin(): int;
}