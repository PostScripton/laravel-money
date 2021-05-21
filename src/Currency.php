<?php

namespace PostScripton\Money;

use Illuminate\Support\Collection;
use PostScripton\Money\Exceptions\BaseException;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\CurrencyHasWrongConstructorException;
use PostScripton\Money\Exceptions\CurrencyListConfigException;
use PostScripton\Money\Exceptions\CustomCurrencyDoesNotHaveFieldException;
use PostScripton\Money\Exceptions\CustomCurrencyTakenCodesException;
use PostScripton\Money\Exceptions\CustomCurrencyWrongFieldTypeException;
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
	public const LIST_CUSTOM = 'custom';
	public const LIST_CONFIG = 'config';

	private const CONFIG_LIST = 'money.currency_list';
	private const CONFIG_CUSTOM = 'money.custom_currencies';
	private static ?string $_list;

	public static function code(string $code): ?Currency
	{
		$currency = is_numeric($code)
			? self::currencies()->firstWhere('num_code', $code)
			: self::currencies()->firstWhere('iso_code', strtoupper($code));

		if (is_null($currency)) {
			throw new CurrencyDoesNotExistException(__METHOD__, 1, '$code', implode(',', [$code, self::$_list]));
		}

		return new self($currency);
	}

	protected static function currencies(): Collection
	{
		$list = is_array(config(self::CONFIG_LIST))
			? self::LIST_CONFIG
			: config(self::CONFIG_LIST);

		if (self::isIncorrectList($list)) {
			throw new CurrencyListConfigException($list);
		}

		if (!self::$currencies) {
			self::setCurrencyList($list);
		}

		return collect(self::$currencies);
	}

	public static function isIncorrectList(string $list): bool
	{
		return !in_array(
			$list,
			[
				self::LIST_ALL,
				self::LIST_POPULAR,
				self::LIST_CONFIG,
				self::LIST_CONFIG,
			]
		);
	}

	public static function setCurrencyList(string $list = self::LIST_POPULAR): void
	{
		if (self::isIncorrectList($list)) {
			$list = self::LIST_POPULAR;
		}
		self::$_list = $list;

		self::checkCustomCurrencies();

		if ($list !== self::LIST_CONFIG) {
			self::$currencies = self::getList($list);
			return;
		}

		// Config list below...

		if (!is_array(config(self::CONFIG_LIST))) {
			self::$currencies = self::getList(config(self::CONFIG_LIST));
			return;
		}

		// Custom currency list
		$custom_list = config(self::CONFIG_LIST);
		self::$currencies = self::getList(self::LIST_ALL);

		self::$currencies = array_filter(
			self::$currencies,
			function ($currency) use (&$custom_list) {
				if (empty($custom_list)) {
					return false;
				}

				foreach ($custom_list as $item) {
					if ($currency['iso_code'] === $item || $currency['num_code'] === $item) {
						$custom_list = array_diff($custom_list, [$item]);
						return true;
					}
				}

				return false;
			}
		);
	}

	public static function currentList(): string
	{
		return self::$_list ?? self::LIST_CONFIG;
	}

	public static function count(): int
	{
		return count(self::$currencies);
	}

	private static function getList(string $list, bool $custom = true)
	{
		$list = require __DIR__ . "/List/" . $list . "_currencies.php";

		return $custom
			? array_merge($list, config(self::CONFIG_CUSTOM))
			: $list;
	}

	private static function checkCustomCurrencies()
	{
		$custom_currencies = config(self::CONFIG_CUSTOM);
		$all = self::getList(self::LIST_ALL, false);

		if (!is_array($custom_currencies)) {
			throw new BaseException('The config property "custom_currencies" must be an array.');
		}

		// Validation
		$validator = [
			'full_name' => ['string'],
			'name' => ['string'],
			'iso_code' => ['string'],
			'num_code' => ['string'],
			'symbol' => ['string', 'array'],
			'position' => ['integer'],
		];
		foreach ($custom_currencies as $custom) {
			foreach ($validator as $field => $rules) {
				if (!array_key_exists($field, $custom)) {
					throw new CustomCurrencyDoesNotHaveFieldException($field);
				}

				$valid = false;
				for ($i = 0, $rule = $rules[$i]; $i < count($rules) && !$valid; $i++) {
					$function = 'is_' . $rule;
					$valid = $function($custom[$field]);
				}
				if (!$valid) {
					throw new CustomCurrencyWrongFieldTypeException(implode(':', [$field, implode('|', $rules)]));
				}
			}
		}

		foreach ($custom_currencies as $key => $custom) {
			$except_current_custom = array_filter(
				$custom_currencies,
				fn($index) => $index !== $key,
				ARRAY_FILTER_USE_KEY
			);

			foreach (array_merge($all, $except_current_custom) as $currency) {
				if (strtoupper($currency['iso_code']) === strtoupper($custom['iso_code']) ||
					strtoupper($currency['num_code']) === strtoupper($custom['num_code'])) {
					throw new CustomCurrencyTakenCodesException(
						implode(',', [$currency['full_name'], $currency['iso_code'], $currency['num_code']])
					);
				}
			}
		}
	}

	// ==================== OBJECT ==================== //

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
		$preferred_symbol = null;
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
}