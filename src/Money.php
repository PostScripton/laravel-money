<?php

namespace PostScripton\Money;

use Carbon\Carbon;
use InvalidArgumentException;
use PostScripton\Money\Calculators\Calculator;
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

    public function __construct(string $amount, Currency|string|null $currency = null)
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException(sprintf('The amount must be a numeric-string, [%s] given', $amount));
        }

        $this->amount = app(Calculator::class)->floor($amount);

        $this->setCurrency(Currency::getOrDefault($currency));
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency|string $currency): self
    {
        $this->currency = Currency::get($currency);

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
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        $this->amount = app(Calculator::class)->add($this->amount, $money->amount);

        return $this;
    }

    public function subtract(self $money): self
    {
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        $this->amount = app(Calculator::class)->subtract($this->amount, $money->amount);

        return $this;
    }

    public function multiply(string $multiplier): self
    {
        $this->amount = app(Calculator::class)->multiply($this->amount, $multiplier);
        $this->amount = app(Calculator::class)->floor($this->amount);

        return $this;
    }

    public function divide(string $divisor): self
    {
        $this->amount = app(Calculator::class)->divide($this->amount, $divisor);
        $this->amount = app(Calculator::class)->floor($this->amount);

        return $this;
    }

    public function rebase(self $money): self
    {
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        $this->amount = $money->amount;

        return $this;
    }

    public function floor(): self
    {
        $amount = app(Calculator::class)->divide($this->amount, static::getDefaultDivisor());
        $amount = app(Calculator::class)->floor($amount);
        $amount = app(Calculator::class)->multiply($amount, static::getDefaultDivisor());

        $this->amount = $amount;

        return $this;
    }

    public function ceil(): self
    {
        $amount = app(Calculator::class)->divide($this->amount, static::getDefaultDivisor());
        $amount = app(Calculator::class)->ceil($amount);
        $amount = app(Calculator::class)->multiply($amount, static::getDefaultDivisor());

        $this->amount = $amount;

        return $this;
    }

    public function absolute(): self
    {
        $this->amount = app(Calculator::class)->absolute($this->amount);

        return $this;
    }

    public function isSameCurrency(Money $money): bool
    {
        return Currencies::same($this->getCurrency(), $money->getCurrency());
    }

    public function isDifferentCurrency(Money $money): bool
    {
        return ! $this->isSameCurrency($money);
    }

    public function isNegative(): bool
    {
        return app(Calculator::class)->compare($this->amount, '0') < 0;
    }

    public function isPositive(): bool
    {
        return app(Calculator::class)->compare($this->amount, '0') > 0;
    }

    public function isZero(): bool
    {
        return app(Calculator::class)->compare($this->amount, '0') === 0;
    }

    public function lessThan(Money $money): bool
    {
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        return app(Calculator::class)->compare($this->amount, $money->amount) < 0;
    }

    public function lessThanOrEqual(Money $money): bool
    {
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        return app(Calculator::class)->compare($this->amount, $money->amount) <= 0;
    }

    public function greaterThan(Money $money): bool
    {
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        return app(Calculator::class)->compare($this->amount, $money->amount) > 0;
    }

    public function greaterThanOrEqual(Money $money): bool
    {
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        return app(Calculator::class)->compare($this->amount, $money->amount) >= 0;
    }

    public function equals(Money $money, bool $strict = true): bool
    {
        if ($strict && $this->isDifferentCurrency($money)) {
            return false;
        }

        return app(Calculator::class)->compare($this->amount, $money->amount) === 0;
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

        $newAmount = app(Calculator::class)->multiply($this->amount, $rate);

        return money($newAmount, $currency);
    }

    public function difference(Money $money): Money
    {
        if ($this->isDifferentCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException($money->getCurrency(), $this->getCurrency());
        }

        return $this->clone()->subtract($money)->absolute();
    }

    public function service(): ServiceInterface
    {
        return app(ServiceInterface::class);
    }
}
