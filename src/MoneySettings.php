<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;
use PostScripton\Money\Exceptions\UndefinedOriginException;

class MoneySettings implements MoneySettingsInterface
{
    public const ORIGIN_INT = 0;
    public const ORIGIN_FLOAT = 1;

    private int $decimals;
    private string $thousands_separator;
    private string $decimal_separator;
    private bool $ends_with_0;
    private bool $space_between;
    private Currency $currency;
    private int $origin;

    private ?Money $money;

    public static function isIncorrectOrigin(int $origin): bool
    {
        return !in_array($origin, [
            self::ORIGIN_INT,
            self::ORIGIN_FLOAT,
        ]);
    }

    public function __construct(
        int $decimals = null,
        string $thousands_separator = null,
        string $decimal_separator = null,
        bool $ends_with_0 = null,
        bool $space_between = null,
        Currency $currency = null,
        int $origin = null
    ) {
        $this->money = null;

        try {
            $this->setDecimals($decimals ?? Money::getDefaultDecimals())
                ->setThousandsSeparator($thousands_separator ?? Money::getDefaultThousandsSeparator())
                ->setDecimalSeparator($decimal_separator ?? Money::getDefaultDecimalSeparator())
                ->setEndsWith0($ends_with_0 ?? Money::getDefaultEndsWith0())
                ->setHasSpaceBetween($space_between ?? Money::getDefaultSpaceBetween())
                ->setCurrency($currency ?? Currency::code(Currency::getConfigCurrency()))
                ->setOrigin($origin ?? Money::getDefaultOrigin());
        } catch (CurrencyDoesNotExistException | ShouldPublishConfigFileException | UndefinedOriginException $e) {
            dd($e->getMessage());
        }
    }

    public function bind(Money $money): self
    {
        $this->money = $money;
        return $this;
    }

    public function unbind(): self
    {
        $this->money->unbind();
        $this->money = null;
        return $this;
    }

    // ========== SETTERS ==========

    public function setDecimals(int $decimals = 1): MoneySettings
    {
        if ($decimals < 0) {
            $decimals = 0;
        }

        $this->decimals = $decimals;
        return $this;
    }

    public function setThousandsSeparator(string $separator): MoneySettings
    {
        $this->thousands_separator = $separator;
        return $this;
    }

    public function setDecimalSeparator(string $separator): MoneySettings
    {
        $this->decimal_separator = $separator;
        return $this;
    }

    public function setEndsWith0(bool $ends = false): MoneySettings
    {
        $this->ends_with_0 = $ends;
        return $this;
    }

    public function setHasSpaceBetween(bool $space = true): MoneySettings
    {
        $this->space_between = $space;
        return $this;
    }

    public function setCurrency(Currency $currency): MoneySettings
    {
        $this->currency = $currency;
        return $this;
    }

    public function setOrigin(int $origin): MoneySettings
    {
        if (self::isIncorrectOrigin($origin)) {
            throw new UndefinedOriginException(__METHOD__, 1, '$origin');
        }

        $_origin = $this->origin ?? self::ORIGIN_INT;
        $this->origin = $origin;

        if (!is_null($this->money)) {
            if ($_origin !== $origin) {
                $number = $_origin === MoneySettings::ORIGIN_INT
                    ? $this->money->getPureNumber() / $this->getDivisor()
                    : $this->money->getPureNumber() * $this->getDivisor();

                $this->money->rebase($number, $origin);
            }
        }

        return $this;
    }

    // ========== GETTERS ==========

    public function getDecimals(): int
    {
        return $this->decimals;
    }

    public function getThousandsSeparator(): string
    {
        return $this->thousands_separator;
    }

    public function getDecimalSeparator(): string
    {
        return $this->decimal_separator;
    }

    public function endsWith0(): bool
    {
        return $this->ends_with_0;
    }

    public function hasSpaceBetween(): bool
    {
        return $this->space_between;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getOrigin(): int
    {
        return $this->origin;
    }
}