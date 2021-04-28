<?php

namespace PostScripton\Money;

use Illuminate\Support\ServiceProvider;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;
use PostScripton\Money\Exceptions\UndefinedOriginException;

class MoneyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'money');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $settings = new MoneySettings();

        try {
            $settings->setDecimals(config('money.decimals', 1))
                ->setThousandsSeparator(config('money.thousands_separator', ' '))
                ->setDecimalSeparator(config('money.decimal_separator', '.'))
                ->setEndsWith0(config('money.ends_with_0', false))
                ->setHasSpaceBetween(config('money.space_between', true))
                ->setCurrency(Currency::code(Currency::getConfigCurrency()))
                ->setOrigin(config('money.origin', MoneySettings::ORIGIN_INT));
        } catch (UndefinedOriginException | CurrencyDoesNotExistException | ShouldPublishConfigFileException $e) {
            dd($e->getMessage());
        }

        Money::set($settings);
    }

    protected function registerPublishing()
    {
        $this->publishes(
            [
                $this->getConfigPath() => config_path('money.php'),
            ],
            'money'
        );
    }

    private function getConfigPath(): string
    {
        return __DIR__ . '/../config/money.php';
    }
}