<?php

namespace PostScripton\Money;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PostScripton\Money\Enums\CurrencyList;
use PostScripton\Money\Exceptions\BaseException;
use PostScripton\Money\Exceptions\CustomCurrencyTakenCodesException;
use PostScripton\Money\Exceptions\CustomCurrencyValidationException;
use PostScripton\Money\Exceptions\ServiceClassDoesNotExistException;
use PostScripton\Money\Exceptions\ServiceDoesNotExistException;
use PostScripton\Money\Exceptions\ServiceDoesNotHaveClassException;
use PostScripton\Money\Exceptions\ServiceDoesNotInheritServiceException;
use PostScripton\Money\Rules\Money as MoneyRule;
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

        $settings = (new MoneySettings())
            ->setDecimals(config('money.decimals', 1))
            ->setThousandsSeparator(config('money.thousands_separator', ' '))
            ->setDecimalSeparator(config('money.decimal_separator', '.'))
            ->setEndsWith0(config('money.ends_with_0', false))
            ->setHasSpaceBetween(config('money.space_between', true));

        Money::set($settings);

        Validator::extend(MoneyRule::RULE_NAME, (MoneyRule::class . '@passes'), app(MoneyRule::class)->message());
    }

    protected function registerService()
    {
        $this->app->bind(ServiceInterface::class, function () {
            $config = config('money.services.' . config('money.service'));

            if (is_null($config)) {
                throw new ServiceDoesNotExistException(config('money.service'));
            }

            if (!array_key_exists('class', $config = $config ?? [])) {
                throw new ServiceDoesNotHaveClassException(config('money.service'));
            }

            $class = $config['class'];

            if (!class_exists($class)) {
                throw new ServiceClassDoesNotExistException($class);
            }

            if (!is_subclass_of($class, ServiceInterface::class)) {
                throw new ServiceDoesNotInheritServiceException($class);
            }

            return new $class($config);
        });
    }

    protected function registerCurrencyList(): void
    {
        $list = config('money.currency_list');

        if (is_array($list)) {
            foreach ($list as $code) {
                if (!is_string($code)) {
                    throw new BaseException('Codes in the config property "currency_list" must be string');
                }
            }
            return;
        }

        if (! $list instanceof CurrencyList) {
            throw new BaseException(
                'The config property "currency_list" must be type of ' . CurrencyList::class . '.'
            );
        }
    }

    protected function registerCustomCurrencies(): void
    {
        $customCurrencies = config('money.custom_currencies');

        if (!is_array($customCurrencies)) {
            throw new BaseException('The config property "custom_currencies" must be an array.');
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
                Rule::in([Currency::POSITION_START, Currency::POSITION_END]),
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
        $customCurrencies = collect(config('money.custom_currencies'));
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
                    implode(',', [$currency['full_name'], $currency['iso_code'], $currency['num_code']]),
                );
            }
        });
    }
}
