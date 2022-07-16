<?php

namespace PostScripton\Money;

use Illuminate\Support\Facades\Validator;
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
}
