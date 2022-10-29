<?php

namespace PostScripton\Money;

use PostScripton\Money\PHPDocs\MoneySettingsInterface;

class MoneySettings implements MoneySettingsInterface
{
    public const MIN_DECIMALS = 1;

    public const MAX_DECIMALS = 4;

    private int $decimals;
    private string $thousandsSeparator;
    private string $decimalSeparator;
    private bool $endsWith0;
    private bool $spaceBetween;

    public function __construct(
        ?int $decimals = null,
        ?string $thousandsSeparator = null,
        ?string $decimalSeparator = null,
        ?bool $endsWith0 = null,
        ?bool $spaceBetween = null,
    ) {
        $this->setDecimals($decimals ?? Money::getDefaultDecimals())
            ->setThousandsSeparator($thousandsSeparator ?? Money::getDefaultThousandsSeparator())
            ->setDecimalSeparator($decimalSeparator ?? Money::getDefaultDecimalSeparator())
            ->setEndsWith0($endsWith0 ?? Money::getDefaultEndsWith0())
            ->setHasSpaceBetween($spaceBetween ?? Money::getDefaultSpaceBetween());
    }

    // ========== SETTERS ==========

    public function setDecimals(int $decimals = self::MIN_DECIMALS): self
    {
        if ($decimals < self::MIN_DECIMALS) {
            $decimals = self::MIN_DECIMALS;
        } elseif ($decimals > self::MAX_DECIMALS) {
            $decimals = self::MAX_DECIMALS;
        }

        $this->decimals = $decimals;
        return $this;
    }

    public function setThousandsSeparator(string $separator): self
    {
        $this->thousandsSeparator = $separator;
        return $this;
    }

    public function setDecimalSeparator(string $separator): self
    {
        $this->decimalSeparator = $separator;
        return $this;
    }

    public function setEndsWith0(bool $ends = false): self
    {
        $this->endsWith0 = $ends;
        return $this;
    }

    public function setHasSpaceBetween(bool $space = true): self
    {
        $this->spaceBetween = $space;
        return $this;
    }

    // ========== GETTERS ==========

    public function getDecimals(): int
    {
        return $this->decimals;
    }

    public function getThousandsSeparator(): string
    {
        return $this->thousandsSeparator;
    }

    public function getDecimalSeparator(): string
    {
        return $this->decimalSeparator;
    }

    public function endsWith0(): bool
    {
        return $this->endsWith0;
    }

    public function hasSpaceBetween(): bool
    {
        return $this->spaceBetween;
    }
}
