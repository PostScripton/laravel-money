<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\NotNumericException;
use PostScripton\Money\Exceptions\UndefinedOriginException;
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
            ? (float)($this->getPureNumber() / $this->getDivisor())
            : $this->getPureNumber();

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

    public function add($number, int $origin = MoneySettings::ORIGIN_INT): Money
    {
        // Error handlers
        if (!is_numeric($number)) {
            throw new NotNumericException(__METHOD__, 1, '$number');
        }
        if (!in_array($origin, MoneySettings::ORIGINS)) {
            throw new UndefinedOriginException(__METHOD__, 2, '$origin');
        }

        $this->number += $this->numberIntoCorrectOrigin($number, $origin);
        return $this;
    }

    public function subtract($number, int $origin = MoneySettings::ORIGIN_INT): Money
    {
        // Error handlers
        if (!is_numeric($number)) {
            throw new NotNumericException(__METHOD__, 1, '$number');
        }
        if (!in_array($origin, MoneySettings::ORIGINS)) {
            throw new UndefinedOriginException(__METHOD__, 2, '$origin');
        }

        $number = $this->numberIntoCorrectOrigin($number, $origin);

        // If less than 0, then result must be 0
        if ($this->getPureNumber() - $number < 0) {
            $number = $this->getPureNumber();
        }

        $this->number -= $number;
        return $this;
    }

    public function rebase($number, int $origin = MoneySettings::ORIGIN_INT): Money
    {
        // Error handlers
        if (!is_numeric($number)) {
            throw new NotNumericException(__METHOD__, 1, '$number');
        }
        if (!in_array($origin, MoneySettings::ORIGINS)) {
            throw new UndefinedOriginException(__METHOD__, 2, '$origin');
        }

        $this->number = $this->numberIntoCorrectOrigin($number, $origin);
        return $this;
    }

    private function numberIntoCorrectOrigin($number, int $origin = MoneySettings::ORIGIN_INT)
    {
        // If origins are not the same
        if ($this->settings->getOrigin() !== $origin) {
            return $this->settings->getOrigin() === MoneySettings::ORIGIN_INT
                ? floor($number * $this->getDivisor()) // $origin is float
                : $number / $this->getDivisor(); // $origin is int
        }

        return $number;
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