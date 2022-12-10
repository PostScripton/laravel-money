<?php

namespace PostScripton\Money\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\MoneyServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected string $configName = 'money';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set([
            'cache' => [
                'default' => 'array',

                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                    'database' => [
                        'driver' => 'database',
                        'table' => 'cache',
                    ],
                    'file' => [
                        'driver' => 'file',
                        'path' => 'test/path/file.txt',
                    ],
                    'memcached' => [
                        'driver' => 'memcached',
                    ],
                    'redis' => [
                        'driver' => 'redis',
                    ],
                ],
            ],
        ]);
    }

    protected function getEnvironmentSetUp($app): void
    {
        // https://github.com/orchestral/testbench/issues/211#issuecomment-360885812

        $app->useEnvironmentPath(__DIR__ . '/..');
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
