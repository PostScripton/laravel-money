<?php

namespace PostScripton\Money;

use Carbon\Carbon;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Exceptions\ServiceException;
use PostScripton\Money\Partials\MoneyStatic;
use PostScripton\Money\PHPDocs\MoneyInterface;
use PostScripton\Money\Services\ServiceInterface;

class Money implements MoneyInterface
{
    use MoneyStatic;

    private string $amount;
    private Currency $currency;
    private ?MoneySettings $settings;

    public function __construct(string $amount, $currency = null, $settings = null)
    {
        $this->amount = $amount;
        $this->setCurrency(self::getDefaultCurrency());

        if (is_null($settings) && ! ($currency instanceof MoneySettings)) {
            $settings = new MoneySettings();
        }

        // No parameters passed
        if (is_null($currency)) {
            $this->settings = $settings;
            return;
        }

        // Is $currency a Currency or Settings?
        if ($currency instanceof Currency) {
            $this->setCurrency($currency);
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

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function clone(): Money
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

        if (! $this->settings()->endsWith0()) {
            $thousands = preg_quote($this->settings()->getThousandsSeparator());
            $decimals = preg_quote($this->settings()->getDecimalSeparator());

            # /^-?((\d+|\s*)*\.\d*[1-9]|(\d+|\s*)*)/ - берёт всё число, кроме 0 и .*0 на конце
            $pattern = '/^-?((\d+|' . $thousands . '*)*' . $decimals . '\d*[1-9]|(\d+|' . $thousands . '*)*)/';
            preg_match($pattern, $money, $money);
            $money = $money[0];
        }

        return $money;
    }

    public function add(Money $money): self
    {
        if (! $this->isSameCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException();
        }

        $this->amount += $money->amount;

        return $this;
    }

    public function subtract(self $money): self
    {
        if (! $this->isSameCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException();
        }

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
        if (! $this->isSameCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException();
        }

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

    public function absolute(): self
    {
        $this->amount = ltrim($this->amount, '-');

        return $this;
    }

    public function isSameCurrency(Money $money): bool
    {
        return $this->getCurrency()->getCode() === $money->getCurrency()->getCode();
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

    public function lessThan(Money $money): bool
    {
        return $this->amount < $money->amount;
    }

    public function lessThanOrEqual(Money $money): bool
    {
        return $this->amount <= $money->amount;
    }

    public function greaterThan(Money $money): bool
    {
        return $this->amount > $money->amount;
    }

    public function greaterThanOrEqual(Money $money): bool
    {
        return $this->amount >= $money->amount;
    }

    public function equals(Money $money, bool $strict = true): bool
    {
        if ($strict) {
            if ($this->getCurrency()->getCode() !== $money->getCurrency()->getCode()) {
                return false;
            }
        }

        return $this->amount === $money->amount;
    }

    public function convertInto(Currency $currency, ?float $rate = null, ?Carbon $date = null): Money
    {
        // Convert online
        if (is_null($rate)) {
            $notSupported = $this->service()->supports([$currency->getCode(), $this->getCurrency()->getCode()]);
            if (! empty($notSupported)) {
                throw new ServiceException(sprintf(
                    'The service class [%s] doesn\'t support one of the currencies [%s]',
                    $this->service()->getClassName(),
                    implode(', ', $notSupported),
                ));
            }

            $rate = $this->service()->rate($this->getCurrency()->getCode(), $currency->getCode(), $date);
        }

        $newAmount = $this->amount * $rate;

        return money($newAmount, $currency, clone $this->settings());
    }

    public function difference(Money $money): Money
    {
        if (! $this->isSameCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException();
        }

        return $this->clone()->subtract($money)->absolute();
    }

    public function toString(): string
    {
        return self::bindMoneyWithCurrency($this, $this->getCurrency());
    }

    public function service(): ServiceInterface
    {
        return app(ServiceInterface::class);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
