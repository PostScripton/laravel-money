<?php

namespace PostScripton\Money;

use Illuminate\Support\ServiceProvider;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\ShouldPublishConfigFileException;

class MoneyServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->registerPublishing();

		try {
			Money::set(
				config('money.thousands_separator', ' '),
				config('money.decimal_separator', '.'),
				config('money.decimals', 1),
				config('money.ends_with_0', false),
				Currency::code(config('money.default_currency', 'RUB'))
			);
		} catch (CurrencyDoesNotExistException | ShouldPublishConfigFileException $e) {
			dd($e->getMessage());
		}
	}

	public function register()
	{
		//
	}

	protected function registerPublishing()
	{
		$this->publishes([
			__DIR__ . '/config/money.php' => config_path('money.php'),
		], 'money');
	}
}