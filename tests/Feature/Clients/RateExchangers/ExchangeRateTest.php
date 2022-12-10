<?php

namespace PostScripton\Money\Tests\Feature\Clients\RateExchangers;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Mockery;
use PostScripton\Money\Clients\RateExchangers\ExchangeRate;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\RateExchangerException;
use PostScripton\Money\MoneyServiceProvider;
use PostScripton\Money\Tests\TestCase;

class ExchangeRateTest extends TestCase
{
    public function testGetLatestRate(): void
    {
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'base' => 'EUR',
                    'date' => '2022-12-22',
                    'rates' => [
                        'USD' => 1.057519,
                    ],
                ],
            ])
            ->getMock();

        $rate = $exchangeRate->rate('EUR', 'USD');

        $this->assertEquals(1.057519, $rate);
    }

    public function testGetHistoricalRate(): void
    {
        Carbon::setTestNow(now());
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs(now()->subYear()->format('Y-m-d'))
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'historical' => true,
                    'base' => 'EUR',
                    'date' => '2021-12-22',
                    'rates' => [
                        'USD' => 1.132761,
                    ],
                ],
            ])
            ->getMock();

        $rate = $exchangeRate->rate('EUR', 'USD', now()->subYear());

        $this->assertEquals(1.132761, $rate);
    }

    public function testGetLatestRates(): void
    {
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'base' => 'EUR',
                    'date' => '2022-12-22',
                    'rates' => [
                        'USD' => 1.057519,
                        'RUB' => 72.57348,
                    ],
                ],
            ])
            ->getMock();

        $rates = $exchangeRate->rate('EUR', ['USD', 'RUB']);

        $this->assertEquals([
            'USD' => 1.057519,
            'RUB' => 72.57348,
        ], $rates);
    }

    public function testGetHistoricalRates(): void
    {
        Carbon::setTestNow(now());
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs(now()->subYear()->format('Y-m-d'))
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'historical' => true,
                    'base' => 'EUR',
                    'date' => '2021-12-22',
                    'rates' => [
                        'USD' => 1.132761,
                        'RUB' => 83.415939,
                    ],
                ],
            ])
            ->getMock();

        $rates = $exchangeRate->rate('EUR', ['USD', 'RUB'], now()->subYear());

        $this->assertEquals([
            'USD' => 1.132761,
            'RUB' => 83.415939,
        ], $rates);
    }

    public function testEmptySupports(): void
    {
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('symbols')
            ->andReturn([
                'code' => 200,
                'result' => [
                    'success' => true,
                    'symbols' => [
                        'AED' => [
                            'description' => 'United Arab Emirates Dirham',
                            'code' => 'AED',
                        ],
                        'BYR' => [
                            'description' => 'Belarusian Ruble',
                            'code' => 'BYR',
                        ],
                        'EUR' => [
                            'description' => 'Euro',
                            'code' => 'EUR',
                        ],
                        'GBP' => [
                            'description' => 'British Pound Sterling',
                            'code' => 'GBP',
                        ],
                        'RUB' => [
                            'description' => 'Russian Ruble',
                            'code' => 'RUB',
                        ],
                        'USD' => [
                            'description' => 'United States Dollar',
                            'code' => 'USD',
                        ],
                    ],
                ],
            ])
            ->getMock();

        $notSupported = $exchangeRate->supports([]);

        $this->assertEmpty($notSupported);
    }

    public function testSupports(): void
    {
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('symbols')
            ->andReturn([
                'code' => 200,
                'result' => [
                    'success' => true,
                    'symbols' => [
                        'AED' => [
                            'description' => 'United Arab Emirates Dirham',
                            'code' => 'AED',
                        ],
                        'BYR' => [
                            'description' => 'Belarusian Ruble',
                            'code' => 'BYR',
                        ],
                        'EUR' => [
                            'description' => 'Euro',
                            'code' => 'EUR',
                        ],
                        'GBP' => [
                            'description' => 'British Pound Sterling',
                            'code' => 'GBP',
                        ],
                        'RUB' => [
                            'description' => 'Russian Ruble',
                            'code' => 'RUB',
                        ],
                        'USD' => [
                            'description' => 'United States Dollar',
                            'code' => 'USD',
                        ],
                    ],
                ],
            ])
            ->getMock();

        $notSupported = $exchangeRate->supports(['USD', 'CNY', 'RUB', 'TRY']);

        $this->assertEquals(['CNY', 'TRY'], $notSupported);
    }

    public function testSupportsThrowsExceptionBecauseCurrencyDoesNotExist(): void
    {
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('symbols')
            ->andReturn([
                'code' => 200,
                'result' => [
                    'success' => true,
                    'symbols' => [
                        'AED' => [
                            'description' => 'United Arab Emirates Dirham',
                            'code' => 'AED',
                        ],
                        'BYR' => [
                            'description' => 'Belarusian Ruble',
                            'code' => 'BYR',
                        ],
                        'EUR' => [
                            'description' => 'Euro',
                            'code' => 'EUR',
                        ],
                        'GBP' => [
                            'description' => 'British Pound Sterling',
                            'code' => 'GBP',
                        ],
                        'RUB' => [
                            'description' => 'Russian Ruble',
                            'code' => 'RUB',
                        ],
                        'USD' => [
                            'description' => 'United States Dollar',
                            'code' => 'USD',
                        ],
                    ],
                ],
            ])
            ->getMock();

        $this->expectException(CurrencyDoesNotExistException::class);

        $exchangeRate->supports(['TEST_1']);
    }

    public function testUnsuccessfulResponseThrowsException(): void
    {
        $result = [
            'success' => false,
            'error_code' => '1234',
            'error' => 'Example error message.',
        ];
        $exchangeRate = Mockery::mock(ExchangeRate::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest')
            ->andReturn([
                'status' => 200,
                'result' => $result,
            ])
            ->getMock();

        $this->expectException(RateExchangerException::class);
        $this->expectExceptionMessage(sprintf('Response error: %s', json_encode($result)));

        $exchangeRate->rate('EUR', 'USD');
    }

    public function testClientConfig(): void
    {
        $client = $this->getClient();
        $expectedConfig = [
            'base_uri' => new Uri($client->getBaseUri()),
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
                'Accepts' => 'application/json',
                'User-Agent' => MoneyServiceProvider::FULL_PACKAGE_NAME_WITH_VERSION,
            ],
        ];

        $config = array_intersect_key($client->getClientConfig(), [
            'base_uri' => '',
            RequestOptions::HEADERS => '',
        ]);

        $this->assertEquals($expectedConfig, $config);
    }

    private function getClient(array $config = []): ExchangeRate
    {
        return new class ($config) extends ExchangeRate
        {
            public function getClientConfig(): array
            {
                return $this->client->getConfig();
            }

            public function getBaseUri(): string
            {
                return $this->baseUri();
            }
        };
    }
}
