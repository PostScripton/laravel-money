<?php

namespace PostScripton\Money;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use InvalidArgumentException;
use PostScripton\Money\Cache\MoneyCache;
use PostScripton\Money\Cache\RateExchangerCache;
use PostScripton\Money\Calculators\BcMathCalculator;
use PostScripton\Money\Calculators\Calculator;
use PostScripton\Money\Calculators\NativeCalculator;
use PostScripton\Money\Clients\RateExchangers\RateExchanger;
use PostScripton\Money\Enums\CurrencyList;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\CustomCurrencyTakenCodesException;
use PostScripton\Money\Exceptions\RateExchangerException;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Rules\Money as MoneyRule;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MoneyServiceProvider extends PackageServiceProvider
{
    public const PACKAGE_NAME = 'laravel-money';

    public const VERSION = '4.0.0';

    public const FULL_PACKAGE_NAME = 'postscripton/' . self::PACKAGE_NAME;

    public const FULL_PACKAGE_NAME_WITH_VERSION = self::FULL_PACKAGE_NAME . ':' . self::VERSION;

    public function configurePackage(Package $package): void
    {
        $package->name(self::PACKAGE_NAME)
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(MoneyCache::class);
        $this->app->singleton(RateExchangerCache::class);

        $this->app->singleton(Calculator::class, function () {
            if (extension_loaded('bcmath')) {
                return new BcMathCalculator();
            }

            return new NativeCalculator();
        });
    }

    public function packageBooted(): void
    {
        $this->registerCurrencyList();
        $this->registerCustomCurrencies();
        $this->registerRateExchanger();

        Money::setFormatter(new DefaultMoneyFormatter());
        Currency::setDefault(currency(config('money.default_currency', 'USD')));

        Validator::extend(MoneyRule::RULE_NAME, (MoneyRule::class . '@passes'), app(MoneyRule::class)->message());

        Blueprint::macro('money', function (string $column = 'amount') {
            return $this->bigInteger($column)->nullable();
        });
        Blueprint::macro('unsignedMoney', function (string $column = 'amount') {
            return $this->unsignedBigInteger($column)->nullable();
        });
    }

    protected function registerRateExchanger(): void
    {
        $this->app->singleton(RateExchanger::class, function () {
            $config = config('money.rate_exchangers.' . config('money.rate_exchanger'));

            if (is_null($config)) {
                throw new RateExchangerException(
                    'The rate exchanger doesn\'t exist in the "rate_exchanger" property.',
                );
            }

            if (! array_key_exists('class', $config)) {
                throw new RateExchangerException('The rate exchanger doesn\'t have the "class" property.');
            }

            $class = $config['class'];
            if (! class_exists($class)) {
                throw new RateExchangerException("The rate exchanger class [{$class}] doesn't exist.");
            }
            if (! is_subclass_of($class, RateExchanger::class)) {
                throw new RateExchangerException(sprintf(
                    'The given rate exchanger class [%s] doesn\'t implement the [%s] interface.',
                    $class,
                    RateExchanger::class,
                ));
            }

            return new $class($config);
        });
    }

    protected function registerCurrencyList(): void
    {
        $list = config('money.currency_list');

        if (is_array($list)) {
            foreach ($list as $code) {
                if (! is_string($code)) {
                    throw new Exception('Codes in the config property "currency_list" must be string');
                }
            }
            return;
        }

        if (! $list instanceof CurrencyList) {
            throw new Exception('The config property "currency_list" must be type of ' . CurrencyList::class . '.');
        }
    }

    protected function registerCustomCurrencies(): void
    {
        $customCurrencies = config('money.custom_currencies');

        if (! is_array($customCurrencies)) {
            throw new Exception('The config property "custom_currencies" must be an array.');
        }
        if (empty($customCurrencies)) {
            return;
        }

        $validator = Validator::make($customCurrencies, [
            '*.full_name' => ['required', 'string'],
            '*.name' => ['required', 'string'],
            '*.iso_code' => ['required', 'string'],
            '*.num_code' => ['required', 'string'],
            '*.symbol' => ['required'],
            '*.symbol.*' => ['required', 'string'],
            '*.position' => [
                'required',
                new Enum(CurrencyPosition::class),
            ],
        ], [], [
            '*.full_name' => 'full name',
            '*.name' => 'name',
            '*.iso_code' => 'ISO code',
            '*.num_code' => 'numeric code',
            '*.symbol' => 'symbol',
            '*.position' => 'position',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first(), 422);
        }

        $this->customCurrenciesShouldNotDuplicate();
    }

    private function customCurrenciesShouldNotDuplicate(): void
    {
        $customCurrencies = CurrencyList::Custom->collection();
        $customCurrencies->each(function (array $currency, int $key) use ($customCurrencies) {
            $withoutCurrent = $customCurrencies->filter(fn($v, $k) => $k !== $key);

            $sameIsoCode = $withoutCurrent->some(function (array $custom) use ($currency) {
                return strtoupper($custom['iso_code']) === strtoupper($currency['iso_code']);
            });
            $sameNumCode = $withoutCurrent->some(function (array $custom) use ($currency) {
                return strtoupper($custom['num_code']) === strtoupper($currency['num_code']);
            });

            if ($sameIsoCode || $sameNumCode) {
                throw new CustomCurrencyTakenCodesException(
                    $currency['full_name'],
                    $currency['iso_code'],
                    $currency['num_code'],
                );
            }
        });
    }
}
