<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\ServiceProviders\MoneyServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
	protected function getPackageProviders($app): array
	{
		return [
			MoneyServiceProvider::class,
		];
	}
}