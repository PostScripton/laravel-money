<?php

namespace PostScripton\Money;

use Carbon\Carbon;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Exceptions\ServiceException;
use PostScripton\Money\PHPDocs\MoneyInterface;
use PostScripton\Money\Services\ServiceInterface;
use PostScripton\Money\Traits\Converter;
use PostScripton\Money\Traits\StaticPart;

class Money implements MoneyInterface
{
    use StaticPart;
    use Converter;

    public final const MIN_DECIMALS = 0;

    public final const MAX_DECIMALS = 4;

    private string $amount;

    private Currency $currency;

    public function __construct(string $amount, ?Currency $currency = null)
    {
        $this->amount = $amount;
        $this->setCurrency($currency ?? self::getDefaultCurrency());
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
        return money($this->amount, $this->getCurrency());
    }

    public function getAmount(): string
    {
        return $this->amount;
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

        return money($newAmount, $currency);
    }

    public function difference(Money $money): Money
    {
        if (! $this->isSameCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException();
        }

        return $this->clone()->subtract($money)->absolute();
    }

    public function service(): ServiceInterface
    {
        return app(ServiceInterface::class);
    }
}
