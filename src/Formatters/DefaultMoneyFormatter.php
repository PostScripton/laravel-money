<?php

namespace PostScripton\Money\Formatters;

use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Money;

class DefaultMoneyFormatter implements MoneyFormatter
{
    private Money $money;

    private int $decimals;

    private string $thousandsSeparator;

    private string $decimalSeparator;

    private bool $endsWithZero;

    private bool $spaceBetween;

    private bool $useCurrency;

    private ?CurrencyDisplay $currencyDisplay = null;

    public function __construct()
    {
        $this->decimals(config('money.decimals'));
        $this->thousandsSeparator = config('money.thousands_separator');
        $this->decimalSeparator = config('money.decimal_separator');
        $this->endsWithZero = config('money.ends_with_0');
        $this->spaceBetween = config('money.space_between');

        $this->useCurrency = true;
    }

    public function thousandsSeparator(string $separator): self
    {
        $this->thousandsSeparator = $separator;

        return $this;
    }

    public function decimalSeparator(string $separator): self
    {
        $this->decimalSeparator = $separator;

        return $this;
    }

    public function decimals(int $decimals): self
    {
        if ($decimals < Money::MIN_DECIMALS) {
            $decimals = Money::MIN_DECIMALS;
        } elseif ($decimals > Money::MAX_DECIMALS) {
            $decimals = Money::MAX_DECIMALS;
        }

        $this->decimals = $decimals;

        return $this;
    }

    public function endsWithZero(bool $ends = true): self
    {
        $this->endsWithZero = $ends;

        return $this;
    }

    public function useCurrency(): self
    {
        $this->useCurrency = true;

        return $this;
    }

    public function dontUseCurrency(): self
    {
        $this->useCurrency = false;

        return $this;
    }

    public function displayCurrencyAs(CurrencyDisplay $mode): self
    {
        $this->currencyDisplay = $mode;

        return $this;
    }

    public function spaceBetweenCurrencyAndAmount(bool $space = true): self
    {
        $this->spaceBetween = $space;

        return $this;
    }

    public function format(Money $money): string
    {
        $this->money = $money;

        $amount = $this->formatAmount($money);

        return $this->useCurrency
            ? $this->attachToCurrency($amount)
            : $amount;
    }

    protected function formatAmount(Money $money): string
    {
        $amount = number_format(
            (float) ($money->getAmount() / Money::getDefaultDivisor()),
            $this->decimals,
            $this->decimalSeparator,
            $this->thousandsSeparator,
        );

        if (! $this->endsWithZero && str_contains($amount, $this->decimalSeparator)) {
            $amount = preg_replace('/0+$/', '', $amount);
            $amount = rtrim($amount, $this->decimalSeparator);
        }

        return $amount;
    }

    protected function attachToCurrency(string $amount): string
    {
        $currency = $this->money->getCurrency();

        $space = $this->spaceBetween ? ' ' : '';
        if ($this->alwaysHasSpace($this->money, $currency)) {
            $space = ' ';
        }

        $symbol = $currency->getDisplayValue($this->currencyDisplay);

        return $currency->getPosition() === CurrencyPosition::Start
            ? $symbol . $space . $amount
            : $amount . $space . $symbol;
    }

    private function alwaysHasSpace(Money $money, Currency $currency): bool
    {
        $currencyDisplay = $this->currencyDisplay ?? $currency->getDisplay();

        return ($currency->getPosition() === CurrencyPosition::Start && $money->isNegative())
               || $currencyDisplay === CurrencyDisplay::Code;
    }
}
