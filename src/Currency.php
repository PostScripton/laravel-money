<?php

namespace PostScripton\Money;

use Exception;
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

    public static function code(string $code): ?Currency
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
        if ($this->display === CurrencyDisplay::Code) {
            return $this->isoCode;
        }

        if (is_array($this->symbol)) {
            if (! array_key_exists($index ?? 0, $this->symbol)) {
                throw new NoSuchCurrencySymbolException();
            }

            if (is_null($index)) {
                if (! is_null($this->preferredSymbol)) {
                    return $this->symbol[$this->preferredSymbol];
                }

                $index = 0;
            }

            return $this->symbol[$index];
        }

        return $this->symbol;
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

    public function setPreferredSymbol(int $index = 0): self
    {
        if (is_array($this->symbol)) {
            if (! array_key_exists($index, $this->symbol)) {
                throw new NoSuchCurrencySymbolException();
            }

            $this->preferredSymbol = $index;
        }

        return $this;
    }

    /** @throws Exception */
    public static function getConfigCurrency(): string
    {
        if (Money::configNotPublished()) {
            throw new Exception('Please publish the config file by running "php artisan vendor:publish --tag=money"');
        }

        return config('money.default_currency', 'USD');
    }
}
