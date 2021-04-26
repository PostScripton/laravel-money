<?php

namespace PostScripton\Money;

use PostScripton\Money\Traits\MoneyFormatter;
use PostScripton\Money\Traits\MoneyStatic;

class Money implements MoneyInterface
{
    use MoneyFormatter;
    use MoneyStatic;

    private float $number;
    public ?MoneySettings $settings;

    public function __construct(float $number, $currency = null, $settings = null)
    {
        $this->number = $number;

        if (is_null($settings) && !($currency instanceof MoneySettings)) {
            $settings = new MoneySettings;
        }

        // No parameters passed
        if (is_null($currency)) {
            $this->settings = $settings;
            return;
        }

        // Only one passed. It may be Currency or Settings
        if ($currency instanceof Currency) {
            $settings->setCurrency($currency);
        } elseif ($currency instanceof MoneySettings) {
            $settings = $currency;
        }

        $this->settings = $settings;
    }

    public function getPureNumber(): float
    {
        return $this->number;
    }

    public function getNumber(): string
    {
        $amount = $this->settings->getOrigin() === MoneySettings::ORIGIN_INT
            ? (float)($this->number / $this->getDivisor())
            : $this->number;

        $money = number_format(
            $amount,
            $this->settings->getDecimals(),
            $this->settings->getDecimalSeparator(),
            $this->settings->getThousandsSeparator()
        );

        if (!$this->settings->endsWith0()) {
            # /^((\d+|\s*)*\.\d*[1-9]|(\d+|\s*)*)/ - берёт всё число, кроме 0 и .*0 на конце
            $pattern = '/^((\d+|' . ($this->settings->getThousandsSeparator() ?: '\s') . '*)*\\' .
                ($this->settings->getDecimalSeparator() ?: '\s') . '\d*[1-9]|(\d+|' .
                ($this->settings->getThousandsSeparator() ?: '\s') . '*)*)/';
            preg_match($pattern, $money, $money);
            $money = $money[0];
        }

        return $money;
    }

    public function convertOfflineInto(Currency $currency, float $coeff): Money
    {
        $new_amount = $this->getPureNumber() * $coeff;
        $settings = clone $this->settings;

        return new self($new_amount, $currency, $settings->setCurrency($currency));
    }

    public function toInteger(): int
    {
        return $this->settings->getOrigin() === MoneySettings::ORIGIN_INT
            ? floor($this->getPureNumber())
            : floor($this->getPureNumber() * $this->getDivisor());
    }

    public function toString(): string
    {
        return self::bindMoneyWithCurrency(
            $this->getNumber(),
            $this->settings->getCurrency(),
            $this->settings->hasSpaceBetween()
        );
    }

    private function getDivisor(): int
    {
        return 10 ** $this->settings->getDecimals();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}