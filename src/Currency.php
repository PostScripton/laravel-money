<?php

namespace PostScripton\Money;

use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\CurrencyHasWrongConstructorException;
use PostScripton\Money\Exceptions\NoSuchCurrencySymbolException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;

class Currency
{
    private string $full_name;
    private string $name;
    private string $iso_code;
    private string $num_code;
    private array|string $symbol;
    private CurrencyPosition $position;
    private CurrencyDisplay $display;
    private ?int $preferred_symbol = null;

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

        $this->full_name = $currency['full_name'];
        $this->name = $currency['name'];
        $this->iso_code = strtoupper($currency['iso_code']);
        $this->num_code = $currency['num_code'];
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
            $list = is_array($list) ? 'config' : $list->name;
            throw new CurrencyDoesNotExistException(__METHOD__, 1, '$code', implode(',', [$code, $list]));
        }

        return $currency;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->iso_code;
    }

    public function getNumCode(): string
    {
        return $this->num_code;
    }

    public function getSymbol(?int $index = null): string
    {
        if ($this->display === CurrencyDisplay::Code) {
            return $this->iso_code;
        }

        if (is_array($this->symbol)) {
            if (!array_key_exists($index ?? 0, $this->symbol)) {
                throw new NoSuchCurrencySymbolException(
                    __METHOD__,
                    1,
                    '$index',
                    implode(',', [$index ?? 0, count($this->symbol) - 1])
                );
            }

            if (is_null($index)) {
                if (!is_null($this->preferred_symbol)) {
                    return $this->symbol[$this->preferred_symbol];
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
            if (!array_key_exists($index, $this->symbol)) {
                throw new NoSuchCurrencySymbolException(
                    __METHOD__,
                    1,
                    '$index',
                    implode(',', [$index, count($this->symbol) - 1])
                );
            }

            $this->preferred_symbol = $index;
        }

        return $this;
    }

    /**
     * @throws ShouldPublishConfigFileException
     */
    public static function getConfigCurrency(): string
    {
        if (Money::configNotPublished()) {
            throw new ShouldPublishConfigFileException();
        }

        return config('money.default_currency', 'USD');
    }
}
