<?php

namespace PostScripton\Money;

use Illuminate\Support\Collection;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\CurrencyHasWrongConstructorException;
use PostScripton\Money\Exceptions\CurrencyListConfigException;
use PostScripton\Money\Exceptions\NoSuchCurrencySymbolException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;

class Currency
{
    protected static array $currencies = [];

    public const POS_START = 0;
    public const POS_END = 1;

    public const DISPLAY_SYMBOL = 10;
    public const DISPLAY_CODE = 11;

    public const LIST_ALL = 'all';
    public const LIST_POPULAR = 'popular';
    private static string $_list;

    public static function code(string $code): ?Currency
    {
        $currency = is_numeric($code)
            ? self::currencies()->firstWhere('num_code', $code)
            : self::currencies()->firstWhere('iso_code', strtoupper($code));

        if (is_null($currency)) {
            throw new CurrencyDoesNotExistException(__METHOD__, 1, '$code', implode(',', [$code, self::$_list]));
        }

        return new Currency($currency);
    }

    protected static function currencies(): Collection
    {
        if (!in_array(config('money.currency_list'), [self::LIST_ALL, self::LIST_POPULAR])) {
            throw new CurrencyListConfigException(config('money.currency_list'));
        }

        if (!self::$currencies) {
            self::setCurrencyList(config('money.currency_list'));
        }

        return collect(self::$currencies);
    }

    public static function setCurrencyList(string $list = self::LIST_POPULAR)
    {
        if ($list !== self::LIST_ALL && $list !== self::LIST_POPULAR) {
            $list = self::LIST_POPULAR;
        }

        self::$currencies = require __DIR__ . "/List/{$list}_currencies.php";
        self::$_list = $list;
    }

    // ==================== OBJECT ==================== //

    private string $full_name;
    private string $name;
    private string $iso_code;
    private string $num_code;
    private $symbol; // array or string
    private int $position;
    private int $display;

    public function __construct(array $currency)
    {
        if (is_null($currency['full_name']) ||
            is_null($currency['name']) ||
            is_null($currency['iso_code']) ||
            is_null($currency['num_code']) ||
            is_null($currency['symbol'])) {
            throw new CurrencyHasWrongConstructorException();
        }

        $this->full_name = $currency['full_name'];
        $this->name = $currency['name'];
        $this->iso_code = $currency['iso_code'];
        $this->num_code = $currency['num_code'];
        $this->symbol = $currency['symbol'];
        $this->position = $currency['position'] ?? self::POS_END;
        $this->display = self::DISPLAY_SYMBOL;
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

    public function getSymbol(int $index = 0): string
    {
        if ($this->display === self::DISPLAY_CODE) {
            return $this->iso_code;
        }

        if (is_array($this->symbol)) {
            if (!array_key_exists($index, $this->symbol)) {
                throw new NoSuchCurrencySymbolException(
                    __METHOD__,
                    1,
                    '$index',
                    implode(',', [$index, count($this->symbol) - 1])
                );
            }

            return $this->symbol[$index];
        }

        return $this->symbol;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getDisplay(): int
    {
        return $this->display;
    }

    public function setPosition(int $position = self::POS_START): self
    {
        if ($position !== self::POS_START && $position !== self::POS_END) {
            $position = self::POS_START;
        }

        $this->position = $position;
        return $this;
    }

    public function setDisplay(int $display = self::DISPLAY_SYMBOL): self
    {
        if ($display !== self::DISPLAY_SYMBOL && $display !== self::DISPLAY_CODE) {
            $display = self::DISPLAY_SYMBOL;
        }

        $this->display = $display;
        return $this;
    }
}