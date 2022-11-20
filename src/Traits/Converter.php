<?php

namespace PostScripton\Money\Traits;

use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Formatters\MoneyFormatter;

trait Converter
{
    // TODO: in PHP 8.2 extract constant DEFAULT_ACCURACY=2 for this trait

    protected static MoneyFormatter $formatter;

    public function toString(?MoneyFormatter $formatter = null): string
    {
        $formatter ??= self::$formatter;

        return $formatter->format($this);
    }

    public function toAmountOnlyString(): string
    {
        $formatter = (new DefaultMoneyFormatter())->dontUseCurrency();

        return $this->toString($formatter);
    }

    public function toDecimalString(int $accuracy = 2): string
    {
        return $this->decimalString($accuracy, useCurrency: false);
    }

    public function toFinanceString(int $accuracy = 2): string
    {
        return $this->decimalString($accuracy, useCurrency: true);
    }

    public static function setFormatter(MoneyFormatter $formatter): void
    {
        self::$formatter = $formatter;
    }

    private function decimalString(int $accuracy, bool $useCurrency): string
    {
        $formatter = (new DefaultMoneyFormatter())
            ->dontUseCurrency()
            ->thousandsSeparator('')
            ->decimalSeparator('.')
            ->decimals($accuracy)
            ->endsWithZero();

        if ($useCurrency) {
            $formatter->useCurrency()
                ->displayCurrencyAs(CurrencyDisplay::Symbol)
                ->spaceBetweenCurrencyAndAmount();
        }

        return $this->toString($formatter);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
