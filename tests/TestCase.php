<?php

namespace PostScripton\Money\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use PostScripton\Money\MoneyServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
	protected function getEnvironmentSetUp($app)
	{
		// https://github.com/orchestral/testbench/issues/211#issuecomment-360885812

		$app->useEnvironmentPath(__DIR__.'/..');
		$app->bootstrapWith([LoadEnvironmentVariables::class]);
		parent::getEnvironmentSetUp($app);
	}

	protected function getPackageProviders($app): array
	{
		return [
			MoneyServiceProvider::class,
		];
	}
}