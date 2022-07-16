<?php

namespace PostScripton\Money\Tests\Feature\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\ServiceClassDoesNotExistException;
use PostScripton\Money\Exceptions\ServiceDoesNotExistException;
use PostScripton\Money\Exceptions\ServiceDoesNotHaveClassException;
use PostScripton\Money\Exceptions\ServiceDoesNotInheritServiceException;
use PostScripton\Money\Exceptions\ServiceDoesNotSupportCurrencyException;
use PostScripton\Money\Services\ExchangeRatesAPIService;
use PostScripton\Money\Services\ExchangeRateService;
use PostScripton\Money\Tests\TestCase;
use stdClass;

class ServicesTest extends TestCase
{
    use FakeService;

    private $backup_config;

    /** @test */
    public function aServiceChangesDependingOnTheConfigValueWhenItCalls()
    {
        $money = money('1000000');

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

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function gettingInfoAboutTheDefaultService()
    {
        $money = money('1000000');

        $this->assertInstanceOf(ExchangeRateService::class, $money->service());
        $this->assertEquals(ExchangeRateService::class, $money->service()->getClassName());
    }

    /** @test */
    public function aServiceDoesNotHaveClass()
    {
        $service = 'money.services.' . config('money.service');
        Config::set($service, array_diff_key(config($service), ['class' => '']));

        $this->expectException(ServiceDoesNotHaveClassException::class);

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function aServiceClassDoesNotExist()
    {
        $service = 'money.services.' . config('money.service');
        Config::set($service, array_merge(config($service), ['class' => 'incorrect_class']));

        $this->expectException(ServiceClassDoesNotExistException::class);

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function aServiceClassDoesNotInheritTheMainOne()
    {
        $service = 'money.services.' . config('money.service');
        Config::set($service, array_merge(config($service), ['class' => stdClass::class]));

        $this->expectException(ServiceDoesNotInheritServiceException::class);

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function theGivenCurrencyIsNotSupportedForConvertingByAService()
    {
        $this->mockService();

        Config::set('money.custom_currencies', [
            [
                'full_name' => 'QWERTY currency',
                'name' => 'qwerty',
                'iso_code' => 'QWERTY',
                'num_code' => '1234',
                'symbol' => 'Q-Y',
                'position' => Currency::POSITION_START,
            ],
        ]);
        Currency::setCurrencyList(Currency::currentList());

        $this->expectException(ServiceDoesNotSupportCurrencyException::class);
        $this->expectExceptionMessage('The service class "SomeServiceClass" doesn\'t support ' .
            'one of the currencies [QWERTY]');

        $money = money('1000000');
        $money->convertInto(currency('1234'));
    }

    /** @test */
    public function convertingBackAndForthMustHaveNoFailsInNumber()
    {
        $this->mockService();

        $rub = money('10000000', currency('rub'));
        $usd = $rub->convertInto(currency('usd'));

        $back_rub = $usd->convertInto(currency('rub'));

        $this->assertFalse($rub->equals($back_rub));
        $this->assertEquals('1 000 ₽', $back_rub->toString());
        $this->assertTrue($rub->isSameCurrency($back_rub));
        $this->assertEquals($rub->toString(), $back_rub->toString());
    }

    /** @test */
    public function historicalConverting()
    {
        $this->mockService();

        $rub = money('10000000', currency('rub'));
        $usd_now = $rub->convertInto(currency('usd'));
        $usd_historical = $rub->convertInto(currency('usd'), null, Carbon::createFromDate(2000, 12, 31));

        $this->assertNotEquals($usd_now->getPureAmount(), $usd_historical->getPureAmount());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->backup_config = Config::get('money');
        Currency::setCurrencyList(Currency::currentList());
        Config::set('money.service', 'exchangerate');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backup_config);
    }
}
