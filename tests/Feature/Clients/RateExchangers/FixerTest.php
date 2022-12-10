<?php

namespace PostScripton\Money\Tests\Feature\Clients\RateExchangers;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Mockery;
use PostScripton\Money\Clients\RateExchangers\Fixer;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\RateExchangerAPIChangedException;
use PostScripton\Money\Exceptions\RateExchangerException;
use PostScripton\Money\MoneyServiceProvider;
use PostScripton\Money\Tests\TestCase;

class FixerTest extends TestCase
{
    public function testGetLatestRate(): void
    {
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'timestamp' => 1671738423,
                    'base' => 'EUR',
                    'date' => '2022-12-22',
                    'rates' => [
                        'USD' => 1.057519,
                    ],
                ],
            ])
            ->getMock();

        $rate = $fixer->rate('EUR', 'USD');

        $this->assertEquals(1.057519, $rate);
    }

    public function testGetHistoricalRate(): void
    {
        Carbon::setTestNow(now());
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs(now()->subYear()->format('Y-m-d'))
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'timestamp' => 1640217599,
                    'historical' => true,
                    'base' => 'EUR',
                    'date' => '2021-12-22',
                    'rates' => [
                        'USD' => 1.132761,
                    ],
                ],
            ])
            ->getMock();

        $rate = $fixer->rate('EUR', 'USD', now()->subYear());

        $this->assertEquals(1.132761, $rate);
    }

    public function testGetLatestRates(): void
    {
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'timestamp' => 1671738423,
                    'base' => 'EUR',
                    'date' => '2022-12-22',
                    'rates' => [
                        'USD' => 1.057519,
                        'RUB' => 72.57348,
                    ],
                ],
            ])
            ->getMock();

        $rates = $fixer->rate('EUR', ['USD', 'RUB']);

        $this->assertEquals([
            'USD' => 1.057519,
            'RUB' => 72.57348,
        ], $rates);
    }

    public function testGetHistoricalRates(): void
    {
        Carbon::setTestNow(now());
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs(now()->subYear()->format('Y-m-d'))
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'timestamp' => 1640217599,
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

        $rates = $fixer->rate('EUR', ['USD', 'RUB'], now()->subYear());

        $this->assertEquals([
            'USD' => 1.132761,
            'RUB' => 83.415939,
        ], $rates);
    }

    public function testEmptySupports(): void
    {
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('symbols')
            ->andReturn([
                'code' => 200,
                'result' => [
                    'success' => true,
                    'symbols' => [
                        'AED' => 'United Arab Emirates Dirham',
                        'BYR' => 'Belarusian Ruble',
                        'EUR' => 'Euro',
                        'GBP' => 'British Pound Sterling',
                        'RUB' => 'Russian Ruble',
                        'USD' => 'United States Dollar',
                    ],
                ],
            ])
            ->getMock();

        $notSupported = $fixer->supports([]);

        $this->assertEmpty($notSupported);
    }

    public function testSupports(): void
    {
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('symbols')
            ->andReturn([
                'code' => 200,
                'result' => [
                    'success' => true,
                    'symbols' => [
                        'AED' => 'United Arab Emirates Dirham',
                        'BYR' => 'Belarusian Ruble',
                        'EUR' => 'Euro',
                        'GBP' => 'British Pound Sterling',
                        'RUB' => 'Russian Ruble',
                        'USD' => 'United States Dollar',
                    ],
                ],
            ])
            ->getMock();

        $notSupported = $fixer->supports(['USD', 'CNY', 'RUB', 'TRY']);

        $this->assertEquals(['CNY', 'TRY'], $notSupported);
    }

    public function testSupportsThrowsExceptionBecauseCurrencyDoesNotExist(): void
    {
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('symbols')
            ->andReturn([
                'code' => 200,
                'result' => [
                    'success' => true,
                    'symbols' => [
                        'AED' => 'United Arab Emirates Dirham',
                        'BYR' => 'Belarusian Ruble',
                        'EUR' => 'Euro',
                        'GBP' => 'British Pound Sterling',
                        'RUB' => 'Russian Ruble',
                        'USD' => 'United States Dollar',
                    ],
                ],
            ])
            ->getMock();

        $this->expectException(CurrencyDoesNotExistException::class);

        $fixer->supports(['TEST_1']);
    }

    public function testGetRateThrowsExceptionBecauseAPIChanged(): void
    {
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->withSomeOfArgs('latest')
            ->andReturn([
                'status' => 200,
                'result' => [
                    'success' => true,
                    'timestamp' => 1671738423,
                    'base' => 'EUR',
                    'date' => '2022-12-22',
                    'nested' => [
                        'rates' => [
                            'USD' => 1.057519,
                        ],
                    ],
                ],
            ])
            ->getMock();

        $this->expectException(RateExchangerAPIChangedException::class);

        $fixer->rate('EUR', 'USD');
    }

    public function testSupportsThrowsExceptionBecauseAPIChanged(): void
    {
        $fixer = Mockery::mock(Fixer::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getRequest')
            ->with('symbols')
            ->andReturn([
                'code' => 200,
                'result' => [
                    'success' => true,
                    'nested' => [
                        'symbols' => [
                            'AED' => 'United Arab Emirates Dirham',
                            'BYR' => 'Belarusian Ruble',
                            'EUR' => 'Euro',
                            'GBP' => 'British Pound Sterling',
                            'RUB' => 'Russian Ruble',
                            'USD' => 'United States Dollar',
                        ],
                    ],
                ],
            ])
            ->getMock();

        $this->expectException(RateExchangerAPIChangedException::class);

        $fixer->supports(['USD', 'CNY', 'RUB', 'TRY']);
    }

    public function testUnsuccessfulResponseThrowsException(): void
    {
        $result = [
            'success' => false,
            'error_code' => '1234',
            'error' => 'Example error message.',
        ];
        $fixer = Mockery::mock(Fixer::class)
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

        $fixer->rate('EUR', 'USD');
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
            ],
            RequestOptions::QUERY => [
                'access_key' => 'XXX',
            ],
        ];

        $config = array_intersect_key($client->getClientConfig(), [
            'base_uri' => '',
            RequestOptions::HEADERS => '',
            RequestOptions::QUERY => '',
        ]);

        $this->assertEquals($expectedConfig, $config);
    }

    public function testBaseUri(): void
    {
        $freePlanClient = $this->getClient([
            'key' => 'XXX',
            'free_plan' => true,
        ]);
        $freePlanUri = new Uri($freePlanClient->getBaseUri());
        $paidPlanClient = $this->getClient([
            'key' => 'XXX',
            'free_plan' => false,
        ]);
        $paidPlanUri = new Uri($paidPlanClient->getBaseUri());
        $noPlanClient = $this->getClient([
            'key' => 'XXX',
        ]);
        $noPlanUri = new Uri($noPlanClient->getBaseUri());

        $this->assertEquals('http', $freePlanUri->getScheme());
        $this->assertEquals('https', $paidPlanUri->getScheme());
        $this->assertEquals('https', $noPlanUri->getScheme());
    }

    private function getClient(array $config = []): Fixer
    {
        return new class ($config) extends Fixer
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
