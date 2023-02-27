<?php

namespace PostScripton\Money\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\Money;
use PostScripton\Money\MoneyServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected string $configName = 'money';

    final protected function assertMoneyEquals(Money|string $expected, Money|string $actual)
    {
        $expected = $expected instanceof Money ? $expected : money_parse($expected);
        $actual = $actual instanceof Money ? $actual : money_parse($actual);

        $this->assertEquals(
            $expected->getAmount(),
            $actual->getAmount(),
            'Monetary amounts are not equal',
        );
        $this->assertEquals(
            $expected->getCurrency()->getCode(),
            $actual->getCurrency()->getCode(),
            'Currencies are not equal',
        );
    }

    final protected function assertMoneyNotEquals(Money|string $expected, Money|string $actual)
    {
        $expected = $expected instanceof Money ? $expected : money_parse($expected);
        $actual = $actual instanceof Money ? $actual : money_parse($actual);

        $this->assertFalse($expected->equals($actual), 'Monetary objects are equal');
    }

    final protected function isExceptionClass(mixed $value): bool
    {
        return is_string($value) && str_ends_with($value, 'Exception');
    }

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
