<?php

namespace PostScripton\Money;

use Illuminate\Support\Collection;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\CurrencyHasWrongConstructorException;
use PostScripton\Money\Exceptions\NoSuchCurrencySymbolException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;

class Currency
{
    // todo use enum instead
    public const POSITION_START = 0;
    public const POSITION_END = 1;

    // todo use enum instead
    public const DISPLAY_SYMBOL = 10;
    public const DISPLAY_CODE = 11;

    // todo use enum instead
    public const LIST_ALL = 'all';
    public const LIST_POPULAR = 'popular';
    public const LIST_CUSTOM = 'custom';

    // todo use collection instead of array
    protected static array $currencies = [];
    private string $full_name;
    private string $name;
    private string $iso_code;
    private string $num_code;
    private $symbol; // array or string
    private int $position;
    private int $display;
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
        $this->position = $currency['position'] ?? self::POSITION_END;
        $this->display = self::DISPLAY_SYMBOL;
        $preferred_symbol = null;
    }

    public static function code(string $code): ?Currency
    {
        if (is_numeric($code)) {
            $currency = self::getCurrencies()
                ->filter(fn(Currency $currency) => $currency->getNumCode() === $code)
                ->first();
        } else {
            $currency = self::getCurrencies()
                ->filter(fn(Currency $currency) => $currency->getCode() === strtoupper($code))
                ->first();
        }

        if (is_null($currency)) {
            $list = config('money.currency_list');
            $list = is_array($list) ? 'config' : $list;
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
        if ($this->display === self::DISPLAY_CODE) {
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getDisplay(): int
    {
        return $this->display;
    }

    public function setPosition(int $position = self::POSITION_START): self
    {
        if ($position !== self::POSITION_START && $position !== self::POSITION_END) {
            $position = self::POSITION_START;
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

    public static function getCurrencies(): Collection
    {
        if (!self::$currencies) {
            self::$currencies = self::createCurrencies(self::loadCurrencies());
        }

        return collect(self::$currencies);
    }

    public static function getCurrencyCodesArray(): array
    {
        return self::getCurrencies()
            ->map(fn(self $currency) => $currency->getCode())
            ->toArray();
    }

    /**
     * @deprecated Will be removed due to no usage of it
     */
    public static function count(): int
    {
        return count(self::$currencies);
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

    private static function loadCurrencies(): array
    {
        $list = config('money.currency_list');

        if (!is_array($list)) {
            return self::getList($list);
        }

        // Custom currency list
        return array_filter(self::getList(self::LIST_ALL), function ($currency) use (&$list) {
            if (empty($list)) {
                return false;
            }

            foreach ($list as $item) {
                if ($currency['iso_code'] === $item || $currency['num_code'] === $item) {
                    $list = array_diff($list, [$item]);
                    return true;
                }
            }

            return false;
        });
    }

    private static function createCurrencies(array $currencies): array
    {
        return array_map(function ($currency) {
            return new self($currency);
        }, $currencies);
    }

    private static function getList(string $list): array
    {
        $list = require __DIR__ . "/Lists/" . $list . "_currencies.php";

        if ($list !== self::LIST_CUSTOM) {
            $customCurrencies = collect(config('money.custom_currencies'));
            $list = array_map(function (array $currency) use ($customCurrencies) {
                $customCurrency = $customCurrencies->first(function (array $customCurrency) use ($currency) {
                    return strtoupper($customCurrency['iso_code']) === strtoupper($currency['iso_code']) ||
                        $customCurrency['num_code'] === $currency['num_code'];
                });
                return $customCurrency ?: $currency;
            }, $list);
        }

        return $list;
    }
}
