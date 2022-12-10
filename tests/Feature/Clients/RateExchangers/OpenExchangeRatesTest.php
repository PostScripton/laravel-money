<?php

namespace PostScripton\Money\Tests\Feature\Clients\RateExchangers;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Mockery;
use PostScripton\Money\Clients\RateExchangers\OpenExchangeRates;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\RateExchangerAPIChangedException;
use PostScripton\Money\MoneyServiceProvider;
use PostScripton\Money\Tests\TestCase;

class OpenExchangeRatesTest extends TestCase
{
    public function testGetLatestRate(): void
    {
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest.json')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'disclaimer' => 'Usage subject to terms: https://openexchangerates.org/terms',
                    'license' => 'https://openexchangerates.org/license',
                    'timestamp' => 1671807594,
                    'base' => 'USD',
                    'rates' => [
                        'RUB' => 68.827999,
                    ],
                ],
            ])
            ->getMock();

        $rate = $openExchangeRates->rate('USD', 'RUB');

        $this->assertEquals(68.827999, $rate);
    }

    public function testGetHistoricalRate(): void
    {
        Carbon::setTestNow(now());
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs(sprintf('historical/%s.json', now()->subYear()->format('Y-m-d')))
            ->andReturn([
                'status' => 200,
                'result' => [
                    'disclaimer' => 'Usage subject to terms: https://openexchangerates.org/terms',
                    'license' => 'https://openexchangerates.org/license',
                    'timestamp' => 1640303976,
                    'base' => 'USD',
                    'rates' => [
                        'RUB' => 73.3338,
                    ],
                ],
            ])
            ->getMock();

        $rate = $openExchangeRates->rate('USD', 'RUB', now()->subYear());

        $this->assertEquals(73.3338, $rate);
    }

    public function testGetLatestRates(): void
    {
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest.json')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'disclaimer' => 'Usage subject to terms: https://openexchangerates.org/terms',
                    'license' => 'https://openexchangerates.org/license',
                    'timestamp' => 1671807599,
                    'base' => 'USD',
                    'rates' => [
                        'EUR' => 0.942531,
                        'RUB' => 68.827999,
                    ],
                ],
            ])
            ->getMock();

        $rates = $openExchangeRates->rate('USD', ['EUR', 'RUB']);

        $this->assertEquals([
            'EUR' => 0.942531,
            'RUB' => 68.827999,
        ], $rates);
    }

    public function testGetHistoricalRates(): void
    {
        Carbon::setTestNow(now());
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs(sprintf('historical/%s.json', now()->subYear()->format('Y-m-d')))
            ->andReturn([
                'status' => 200,
                'result' => [
                    'disclaimer' => 'Usage subject to terms: https://openexchangerates.org/terms',
                    'license' => 'https://openexchangerates.org/license',
                    'timestamp' => 1640303998,
                    'base' => 'USD',
                    'rates' => [
                        'EUR' => 0.883146,
                        'RUB' => 73.3338,
                    ],
                ],
            ])
            ->getMock();

        $rates = $openExchangeRates->rate('USD', ['EUR', 'RUB'], now()->subYear());

        $this->assertEquals([
            'EUR' => 0.883146,
            'RUB' => 73.3338,
        ], $rates);
    }

    public function testEmptySupports(): void
    {
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('currencies.json')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'AED' => 'United Arab Emirates Dirham',
                    'BYR' => 'Belarusian Ruble',
                    'EUR' => 'Euro',
                    'GBP' => 'British Pound Sterling',
                    'RUB' => 'Russian Ruble',
                    'USD' => 'United States Dollar',
                ],
            ])
            ->getMock();

        $notSupported = $openExchangeRates->supports([]);

        $this->assertEmpty($notSupported);
    }

    public function testSupports(): void
    {
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('currencies.json')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'AED' => 'United Arab Emirates Dirham',
                    'BYR' => 'Belarusian Ruble',
                    'EUR' => 'Euro',
                    'GBP' => 'British Pound Sterling',
                    'RUB' => 'Russian Ruble',
                    'USD' => 'United States Dollar',
                ],
            ])
            ->getMock();

        $notSupported = $openExchangeRates->supports(['USD', 'CNY', 'RUB', 'TRY']);

        $this->assertEquals(['CNY', 'TRY'], $notSupported);
    }

    public function testSupportsThrowsExceptionBecauseCurrencyDoesNotExist(): void
    {
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('currencies.json')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'AED' => 'United Arab Emirates Dirham',
                    'BYR' => 'Belarusian Ruble',
                    'EUR' => 'Euro',
                    'GBP' => 'British Pound Sterling',
                    'RUB' => 'Russian Ruble',
                    'USD' => 'United States Dollar',
                ],
            ])
            ->getMock();

        $this->expectException(CurrencyDoesNotExistException::class);

        $openExchangeRates->supports(['TEST_1']);
    }

    public function testGetRateThrowsExceptionBecauseAPIChanged(): void
    {
        $openExchangeRates = Mockery::mock(OpenExchangeRates::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest.json')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'disclaimer' => 'Usage subject to terms: https://openexchangerates.org/terms',
                    'license' => 'https://openexchangerates.org/license',
                    'timestamp' => 1671807594,
                    'base' => 'USD',
                    'nested' => [
                        'rates' => [
                            'RUB' => 68.827999,
                        ],
                    ],
                ],
            ])
            ->getMock();

        $this->expectException(RateExchangerAPIChangedException::class);

        $openExchangeRates->rate('USD', 'RUB');
    }

    public function testClientConfig(): void
    {
        $client = $this->getClient([
            'key' => 'XXX',
        ]);
        $expectedConfig = [
            'base_uri' => new Uri($client->getBaseUri()),
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
                'Accepts' => 'application/json',
                'User-Agent' => MoneyServiceProvider::FULL_PACKAGE_NAME_WITH_VERSION,
                'Authorization' => 'Token XXX',
            ],
        ];

        $config = array_intersect_key($client->getClientConfig(), [
            'base_uri' => '',
            RequestOptions::HEADERS => '',
        ]);

        $this->assertEquals($expectedConfig, $config);
    }

    private function getClient(array $config = []): OpenExchangeRates
    {
        return new class ($config) extends OpenExchangeRates
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
