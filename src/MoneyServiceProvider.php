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
			Money::set(' ', '.', 1, false, Currency::code('RUB'));
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