<?php

namespace PostScripton\Money;

use Carbon\Carbon;
use PostScripton\Money\Exceptions\ServiceDoesNotSupportCurrencyException;
use PostScripton\Money\Partials\MoneyHelpers;
use PostScripton\Money\Partials\MoneyStatic;
use PostScripton\Money\PHPDocs\MoneyInterface;
use PostScripton\Money\Services\ServiceInterface;

class Money implements MoneyInterface
{
    use MoneyStatic;
    use MoneyHelpers;

    public const FREQUENT_THOUSAND_SEPARATORS = [' ', '.', ',', '\''];
    public const FREQUENT_DECIMAL_SEPARATORS = ['.', ','];

    private string $amount;
    private ?MoneySettings $settings;

    public function __construct(string $amount, $currency = null, $settings = null)
    {
        $this->amount = $amount;
        $this->settings = null;

        if (is_null($settings) && !($currency instanceof MoneySettings)) {
            $settings = new MoneySettings();
        }

        // No parameters passed
        if (is_null($currency)) {
            $this->settings = $settings;
            return;
        }

        // Is $currency a Currency or Settings?
        if ($currency instanceof Currency) {
            $settings->setCurrency($currency);
        } elseif ($currency instanceof MoneySettings) {
            $settings = $currency;
        }

        $this->bind($settings);
    }

    public function bind(MoneySettings $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    public function settings(): MoneySettings
    {
        return $this->settings;
    }

    public function clone(): self
    {
        return money($this->amount, $this->getCurrency(), clone $this->settings());
    }

    public function getPureAmount(): string
    {
        return $this->amount;
    }

    public function getAmount(): string
    {
        $money = number_format(
            (float)($this->getPureAmount() / self::getDefaultDivisor()),
            $this->settings()->getDecimals(),
            $this->settings()->getDecimalSeparator(),
            $this->settings()->getThousandsSeparator()
        );

        if (!$this->settings()->endsWith0()) {
            $thousands = preg_quote($this->settings()->getThousandsSeparator());
            $decimals = preg_quote($this->settings()->getDecimalSeparator());

            # /^-?((\d+|\s*)*\.\d*[1-9]|(\d+|\s*)*)/ - берёт всё число, кроме 0 и .*0 на конце
            $pattern = '/^-?((\d+|' . $thousands . '*)*' . $decimals . '\d*[1-9]|(\d+|' . $thousands . '*)*)/';
            preg_match($pattern, $money, $money);
            $money = $money[0];
        }

        return $money;
    }

    public function getCurrency(): Currency
    {
        return $this->settings()->getCurrency();
    }

    public function add(self $money): self
    {
        $this->validateMoney($money, __METHOD__);
        $this->amount += $money->amount;

        return $this;
    }

    public function subtract(self $money): self
    {
        $this->validateMoney($money, __METHOD__);
        $this->amount -= $money->amount;

        return $this;
    }

    public function multiply(float $number): self
    {
        $this->amount *= $number;

        return $this;
    }

    public function divide(float $number): self
    {
        $this->amount /= $number;

        return $this;
    }

    public function rebase(self $money): self
    {
        $this->validateMoney($money, __METHOD__);
        $this->amount = $money->amount;

        return $this;
    }

    public function floor(): self
    {
        $this->amount = floor($this->amount / self::getDefaultDivisor()) * self::getDefaultDivisor();

        return $this;
    }

    public function ceil(): self
    {
        $this->amount = ceil($this->amount / self::getDefaultDivisor()) * self::getDefaultDivisor();

        return $this;
    }

    public function isSameCurrency(self $money): bool
    {
        return $this->settings()->getCurrency()->getCode() === $money->settings()->getCurrency()->getCode();
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public function isEmpty(): bool
    {
        return empty($this->amount);
    }

    public function lessThan(self $money): bool
    {
        return $this->amount < $money->amount;
    }

    public function lessThanOrEqual(self $money): bool
    {
        return $this->amount <= $money->amount;
    }

    public function greaterThan(self $money): bool
    {
        return $this->amount > $money->amount;
    }

    public function greaterThanOrEqual(self $money): bool
    {
        return $this->amount >= $money->amount;
    }

    public function equals(self $money, bool $strict = true): bool
    {
        if ($strict) {
            if ($this->getCurrency()->getCode() !== $money->getCurrency()->getCode()) {
                return false;
            }
        }

        return $this->amount === $money->amount;
    }

    public function convertInto(Currency $currency, ?float $rate = null, ?Carbon $date = null): self
    {
        // Convert online
        if (is_null($rate)) {
            $not_supported = $this->service()->supports([$currency->getCode(), $this->getCurrency()->getCode()]);
            if (!empty($not_supported)) {
                throw new ServiceDoesNotSupportCurrencyException($not_supported, $this->service()->getClassName());
            }

            $rate = $this->service()->rate($this->getCurrency()->getCode(), $currency->getCode(), $date);
        }

        $new_amount = $this->amount * $rate;

        return money($new_amount, $currency, clone $this->settings());
    }

    public function difference(self $money, ?MoneySettings $settings = null): string
    {
        $this->validateMoney($money, __METHOD__);
        $amount = $this->amount - $money->amount;
        $settings = is_null($settings) ? clone $this->settings() : $settings;

        return money($amount, $this->getCurrency(), $settings)->toString();
    }

    public function toString(): string
    {
        return self::bindMoneyWithCurrency($this, $this->settings()->getCurrency());
    }

    public function service()
    {
        return app(ServiceInterface::class);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
