<?php

namespace PostScripton\Money;

use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\CurrencyHasWrongConstructorException;
use PostScripton\Money\Exceptions\NoSuchCurrencySymbolException;

class Currency
{
    private string $fullName;
    private string $name;
    private string $isoCode;
    private string $numCode;
    private array|string $symbol;
    private CurrencyPosition $position;
    private CurrencyDisplay $display;
    private ?int $preferredSymbol = null;

    private static self $defaultCurrency;

    public function __construct(array $currency)
    {
        if (
            (! isset($currency['full_name'])) ||
            (! isset($currency['name'])) ||
            (! isset($currency['iso_code'])) ||
            (! isset($currency['num_code'])) ||
            (! isset($currency['symbol']))
        ) {
            throw new CurrencyHasWrongConstructorException();
        }

        $this->fullName = $currency['full_name'];
        $this->name = $currency['name'];
        $this->isoCode = strtoupper($currency['iso_code']);
        $this->numCode = $currency['num_code'];
        $this->symbol = $currency['symbol'];
        $this->position = $currency['position'] ?? CurrencyPosition::End;
        $this->display = CurrencyDisplay::Symbol;
    }

    public static function code(string $code): ?self
    {
        if (is_numeric($code)) {
            $currency = Currencies::get()
                ->filter(fn(self $currency) => $currency->getNumCode() === $code)
                ->first();
        } else {
            $currency = Currencies::get()
                ->filter(fn(self $currency) => $currency->getCode() === strtoupper($code))
                ->first();
        }

        if (is_null($currency)) {
            $list = config('money.currency_list');
            $list = is_array($list) ? 'Config' : $list->name;

            throw new CurrencyDoesNotExistException($code, $list);
        }

        return $currency;
    }

    public static function get(Currency|string|null $currency): ?self
    {
        if (is_string($currency)) {
            return self::code($currency);
        }

        return $currency;
    }

    public static function getOrDefault(Currency|string|null $currency): self
    {
        return self::get($currency) ?? self::getDefault();
    }

    public static function getDefault(): self
    {
        return self::$defaultCurrency;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->isoCode;
    }

    public function getNumCode(): string
    {
        return $this->numCode;
    }

    public function getSymbol(?int $index = null): string
    {
        if (is_string($this->symbol)) {
            return $this->symbol;
        }

        if (is_null($index)) {
            if (! is_null($this->preferredSymbol)) {
                return $this->symbol[$this->preferredSymbol];
            }

            $index = 0;
        }

        if (! array_key_exists($index, $this->symbol)) {
            throw new NoSuchCurrencySymbolException();
        }

        return $this->symbol[$index];
    }

    public function getSymbols(): array
    {
        if (is_array($this->symbol)) {
            return $this->symbol;
        }

        return [$this->symbol];
    }

    public function getPosition(): CurrencyPosition
    {
        return $this->position;
    }

    public function getDisplay(): CurrencyDisplay
    {
        return $this->display;
    }

    public function setPosition(CurrencyPosition $position = CurrencyPosition::Start): self
    {
        $this->position = $position;

        return $this;
    }

    public function setDisplay(CurrencyDisplay $display = CurrencyDisplay::Symbol): self
    {
        $this->display = $display;

        return $this;
    }

    public function setPreferredSymbol(?int $index = null): self
    {
        if (is_string($this->symbol)) {
            return $this;
        }

        if (! is_null($index) && ! array_key_exists($index, $this->symbol)) {
            throw new NoSuchCurrencySymbolException();
        }

        $this->preferredSymbol = $index;

        return $this;
    }

    public function getDisplayValue(?CurrencyDisplay $currencyDisplay = null): string
    {
        return match ($currencyDisplay ?? $this->getDisplay()) {
            CurrencyDisplay::Symbol => $this->getSymbol(),
            CurrencyDisplay::Code => $this->getCode(),
        };
    }

    public static function setDefault(Currency $currency): void
    {
        self::$defaultCurrency = $currency;
    }
}
