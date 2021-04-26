<?php

namespace PostScripton\Money;

use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;

class Currency
{
    protected static array $currencies = [];

    public const POS_START = 0;
    public const POS_END = 1;

    public static function code(string $code): ?Currency
    {
        $code = strtoupper($code);

        if (!array_key_exists($code, self::all())) {
            throw new CurrencyDoesNotExistException(__METHOD__, 1, '$code', $code);
        }

        $currency = self::all()[$code];

        return new Currency($code, $currency['symbol'], $currency['countries'], $currency['position']);
    }

    protected static function all(): array
    {
        if (!static::$currencies) {
            static::$currencies = require __DIR__ . '/List/currencies.php';
        }
        return static::$currencies;
    }

    // ==================== OBJECT ==================== //

    private string $code;
    private string $symbol;
    private array $countries;
    private string $position;

    public function __construct(string $code, string $symbol, array $countries = [], ?string $position = null)
    {
        $this->code = $code;
        $this->symbol = $symbol;
        $this->countries = $countries;
        $this->position = $position ?? self::POS_START;
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): Currency
    {
        if ($position !== self::POS_START || $position !== self::POS_END) {
            $position = self::POS_START;
        }

        $this->position = $position;
        return $this;
    }
}