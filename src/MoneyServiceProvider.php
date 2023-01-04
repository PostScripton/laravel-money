<?php

namespace PostScripton\Money;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use PostScripton\Money\Calculators\BcMathCalculator;
use PostScripton\Money\Calculators\Calculator;
use PostScripton\Money\Enums\CurrencyList;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Exceptions\CustomCurrencyTakenCodesException;
use PostScripton\Money\Exceptions\CustomCurrencyValidationException;
use PostScripton\Money\Exceptions\ServiceException;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Rules\Money as MoneyRule;
use PostScripton\Money\Services\AbstractService;
use PostScripton\Money\Services\ServiceInterface;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MoneyServiceProvider extends PackageServiceProvider
{
    public const PACKAGE_NAME = 'laravel-money';

    public function configurePackage(Package $package): void
    {
        $package->name(self::PACKAGE_NAME)
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        $this->registerService();
        $this->registerCurrencyList();
        $this->registerCustomCurrencies();

        $this->app->bind(Calculator::class, function () {
            return new BcMathCalculator();
        });

        Money::setFormatter(new DefaultMoneyFormatter());
        Money::setDefaultCurrency(currency(config('money.default_currency', 'USD')));

        Validator::extend(MoneyRule::RULE_NAME, (MoneyRule::class . '@passes'), app(MoneyRule::class)->message());

        Blueprint::macro('money', function (string $column = 'price') {
            return $this->bigInteger($column)->nullable();
        });
    }

    protected function registerService(): void
    {
        $this->app->bind(ServiceInterface::class, function () {
            $config = config('money.services.' . config('money.service'));

            if (is_null($config)) {
                throw new ServiceException(sprintf(
                    'The service [%s] doesn\'t exist in the "services" property.',
                    config('money.service'),
                ));
            }

            if (! array_key_exists('class', $config ??= [])) {
                throw new ServiceException(sprintf(
                    'The service [%s] doesn\'t have the "class" property.',
                    config('money.service'),
                ));
            }

            $class = $config['class'];
            if (! class_exists($class)) {
                throw new ServiceException("The service class [{$class}] doesn't exist.");
            }
            if (! is_subclass_of($class, ServiceInterface::class)) {
                throw new ServiceException(sprintf(
                    'The given service class [%s] doesn\'t inherit the [%s].',
                    $class,
                    AbstractService::class,
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
            throw new CustomCurrencyValidationException($validator->errors()->first());
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
