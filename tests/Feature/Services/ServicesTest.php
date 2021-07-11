<?php

namespace PostScripton\Money\Tests\Feature\Services;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\ServiceDoesNotExistException;
use PostScripton\Money\Services\ExchangeRatesAPIService;
use PostScripton\Money\Services\ExchangeRateService;
use PostScripton\Money\Tests\TestCase;

class ServicesTest extends TestCase
{
    private $backup_config;

    /** @test */
    public function aServiceChangesDependingOnTheConfigValueWhenItCalls()
    {
        $money = money(1000);

        Config::set('money.service', 'exchangerate');
        $this->assertInstanceOf(ExchangeRateService::class, $money->service());

        Config::set('money.service', 'exchangeratesapi');
        $this->assertInstanceOf(ExchangeRatesAPIService::class, $money->service());
    }

    /** @test */
    public function aServiceDoesNotExist()
    {
        Config::set('money.service', 'qwerty');

        $this->expectException(ServiceDoesNotExistException::class);

        $money = money(1000);
        $money->convertInto(currency('rub'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->backup_config = Config::get('money');
        Currency::setCurrencyList(Currency::currentList());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backup_config);
    }
}
