<?php

namespace PostScripton\Money;

use Illuminate\Support\ServiceProvider;
use PostScripton\Money\Exceptions\ServiceClassDoesNotExistException;
use PostScripton\Money\Exceptions\ServiceDoesNotExistException;
use PostScripton\Money\Exceptions\ServiceDoesNotHaveClassException;
use PostScripton\Money\Exceptions\ServiceDoesNotInheritServiceException;
use PostScripton\Money\Exceptions\UndefinedOriginException;
use PostScripton\Money\Services\ServiceInterface;

class MoneyServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->mergeConfigFrom($this->getConfigPath(), 'money');

		$this->registerService();

		if ($this->app->runningInConsole()) {
			$this->registerPublishing();
		}

		try {
			$settings = (new MoneySettings())
				->setDecimals(config('money.decimals', 1))
				->setThousandsSeparator(config('money.thousands_separator', ' '))
				->setDecimalSeparator(config('money.decimal_separator', '.'))
				->setEndsWith0(config('money.ends_with_0', false))
				->setHasSpaceBetween(config('money.space_between', true))
				->setOrigin(config('money.origin', MoneySettings::ORIGIN_INT));
		} catch (UndefinedOriginException $e) {
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

	protected function registerService()
	{
		$this->app->bind(ServiceInterface::class, function ($app) {
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

	private function getConfigPath(): string
	{
		return __DIR__ . '/../config/money.php';
	}
}