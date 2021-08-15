<?php

namespace PostScripton\Money;

use Carbon\Carbon;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Exceptions\NotNumericOrMoneyException;
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

    private float $amount;
    private ?MoneySettings $settings;

    public function __construct(float $amount, $currency = null, $settings = null)
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

        if ($settings->bound()) {
            $settings = clone $settings;
        }
        $this->bind($settings);
    }

    public function bind(MoneySettings $settings): self
    {
        if (!is_null($this->settings)) {
            $this->settings()->unbind();
        }

        $this->settings = $settings;
        $this->settings()->bind($this);
        return $this;
    }

    public function unbind(): self
    {
        // Can't exist without Settings
        $this->settings = clone $this->settings;
        $this->settings()->bind($this);
        return $this;
    }

    public function settings(): MoneySettings
    {
        return $this->settings;
    }

    public function getPureAmount(): float
    {
        return $this->amount;
    }

    public function getAmount(): string
    {
        $amount = $this->settings()->getOrigin() === MoneySettings::ORIGIN_INT
            ? (float)($this->getPureAmount() / $this->getDivisor())
            : $this->getPureAmount();

        $money = number_format(
            $amount,
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

    public function add($money, int $origin = MoneySettings::ORIGIN_INT): self
    {
        $this->amount += $this->numberIntoCorrectOrigin($money, $origin, __METHOD__);
        return $this;
    }

    public function subtract($money, int $origin = MoneySettings::ORIGIN_INT): self
    {
        $this->amount -= $this->numberIntoCorrectOrigin($money, $origin, __METHOD__);
        return $this;
    }

    public function multiple(float $number): self
    {
        $this->amount = $this->getPureAmount() * $number;
        return $this;
    }

    public function divide(float $number): self
    {
        $this->amount = $this->getPureAmount() / $number;
        return $this;
    }

    public function rebase($money, int $origin = MoneySettings::ORIGIN_INT): self
    {
        $this->amount = $this->numberIntoCorrectOrigin($money, $origin, __METHOD__);
        return $this;
    }

    public function floor(): self
    {
        $this->amount = $this->settings()->getOrigin() === MoneySettings::ORIGIN_INT
            ? floor($this->getPureAmount() / $this->getDivisor()) * $this->getDivisor()
            : floor($this->getPureAmount());

        return $this;
    }

    public function isSameCurrency(self $money): bool
    {
        return $this->settings()->getCurrency()->getCode() === $money->settings()->getCurrency()->getCode();
    }

    public function isNegative(): bool
    {
        return $this->getPureAmount() < 0;
    }

    public function isPositive(): bool
    {
        return $this->getPureAmount() > 0;
    }

    public function isEmpty(): bool
    {
        return empty($this->getPureAmount());
    }

    public function lessThan($money, int $origin = MoneySettings::ORIGIN_INT): bool
    {
        if (!is_numeric($money) && !$money instanceof self) {
            throw new NotNumericOrMoneyException(__METHOD__, 1, '$money');
        }

        if (is_numeric($money)) {
            $money = $this->numberIntoCorrectOrigin($money, $origin);
        }

        if ($money instanceof self) {
            $money = $money->getPureAmount();
        }

        return $this->getPureAmount() < $money;
    }

    public function lessThanOrEqual($money, int $origin = MoneySettings::ORIGIN_INT): bool
    {
        if (!is_numeric($money) && !$money instanceof self) {
            throw new NotNumericOrMoneyException(__METHOD__, 1, '$money');
        }

        if (is_numeric($money)) {
            $money = $this->numberIntoCorrectOrigin($money, $origin);
        }

        if ($money instanceof self) {
            $money = $money->getPureAmount();
        }

        return $this->getPureAmount() <= $money;
    }

    public function greaterThan($money, int $origin = MoneySettings::ORIGIN_INT): bool
    {
        if (!is_numeric($money) && !$money instanceof self) {
            throw new NotNumericOrMoneyException(__METHOD__, 1, '$money');
        }

        if (is_numeric($money)) {
            $money = $this->numberIntoCorrectOrigin($money, $origin);
        }

        if ($money instanceof self) {
            $money = $money->getPureAmount();
        }

        return $this->getPureAmount() > $money;
    }

    public function greaterThanOrEqual($money, int $origin = MoneySettings::ORIGIN_INT): bool
    {
        if (!is_numeric($money) && !$money instanceof self) {
            throw new NotNumericOrMoneyException(__METHOD__, 1, '$money');
        }

        if (is_numeric($money)) {
            $money = $this->numberIntoCorrectOrigin($money, $origin);
        }

        if ($money instanceof self) {
            $money = $money->getPureAmount();
        }

        return $this->getPureAmount() >= $money;
    }

    public function equals(self $money, bool $strict = true): bool
    {
        return $strict ? $this === $money : $this == $money;
    }

    public function convertInto(Currency $currency, ?float $rate = null, ?Carbon $date = null): self
    {
        // Convert online
        if (is_null($rate)) {
            if (!$this->service()->supports($currency->getCode())) {
                throw new ServiceDoesNotSupportCurrencyException($this->service()->getClassName());
            }

            $rate = $this->service()->rate($this->getCurrency()->getCode(), $currency->getCode(), $date);
        }

        $new_amount = $this->getPureAmount() * $rate;
        $settings = clone $this->settings;

        return money($new_amount, $currency, $settings);
    }

    public function difference(self $money, ?MoneySettings $settings = null): string
    {
        if (!$this->isSameCurrency($money)) {
            throw new MoneyHasDifferentCurrenciesException(__METHOD__, 1, '$money');
        }

        $money_amount = $this->numberIntoCorrectOrigin($money, $money->settings()->getOrigin(), __METHOD__);
        $amount = $this->getPureAmount() - $money_amount;
        $settings = is_null($settings) ? clone $this->settings() : $settings;

        return money($amount, $this->getCurrency(), $settings)->toString();
    }

    public function upload()
    {
        return $this->settings()->getOrigin() === MoneySettings::ORIGIN_INT
            ? (int)floor($this->getPureAmount())
            : (float)floor($this->getPureAmount() * $this->getDivisor()) / $this->getDivisor();
    }

    public function toString(): string
    {
        return self::bindMoneyWithCurrency($this, $this->settings()->getCurrency());
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
