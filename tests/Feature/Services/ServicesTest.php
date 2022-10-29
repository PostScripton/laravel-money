<?php

namespace PostScripton\Money\Tests\Feature\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\Exceptions\ServiceException;
use PostScripton\Money\Services\AbstractService;
use PostScripton\Money\Services\ExchangeRatesAPIService;
use PostScripton\Money\Services\ExchangeRateService;
use PostScripton\Money\Tests\TestCase;
use stdClass;

class ServicesTest extends TestCase
{
    use FakeService;

    private $backupConfig;

    /** @test */
    public function aServiceChangesDependingOnTheConfigValueWhenItCalls(): void
    {
        $money = money('1000000');

        Config::set('money.service', 'exchangerate');
        $this->assertInstanceOf(ExchangeRateService::class, $money->service());

        Config::set('money.service', 'exchangeratesapi');
        $this->assertInstanceOf(ExchangeRatesAPIService::class, $money->service());
    }

    /** @test */
    public function aServiceDoesNotExist(): void
    {
        $service = 'qwerty';
        Config::set('money.service', $service);

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'The service [%s] doesn\'t exist in the "services" property.',
            $service,
        ));

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function gettingInfoAboutTheDefaultService(): void
    {
        $money = money('1000000');

        $this->assertInstanceOf(ExchangeRateService::class, $money->service());
        $this->assertEquals(ExchangeRateService::class, $money->service()->getClassName());
    }

    /** @test */
    public function aServiceDoesNotHaveClass(): void
    {
        $service = config('money.service');
        $serviceConfig = "money.services.{$service}";
        Config::set($serviceConfig, array_diff_key(config($serviceConfig), ['class' => '']));

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'The service [%s] doesn\'t have the "class" property.',
            $service,
        ));

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function aServiceClassDoesNotExist(): void
    {
        $serviceConfig = 'money.services.' . config('money.service');
        $class = 'incorrect_class';
        Config::set($serviceConfig, array_merge(config($serviceConfig), ['class' => $class]));

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("The service class [{$class}] doesn't exist.");

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function aServiceClassDoesNotInheritTheMainOne(): void
    {
        $serviceConfig = 'money.services.' . config('money.service');
        $class = stdClass::class;
        Config::set($serviceConfig, array_merge(config($serviceConfig), ['class' => $class]));

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'The given service class [%s] doesn\'t inherit the [%s].',
            $class,
            AbstractService::class,
        ));

        $money = money('1000000');
        $money->convertInto(currency('rub'));
    }

    /** @test */
    public function theGivenCurrencyIsNotSupportedForConvertingByAService(): void
    {
        $this->mockService();
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('The service class [SomeServiceClass] doesn\'t support ' .
            'one of the currencies [EUR]');

        $money = money('1000000');
        $money->convertInto(currency('EUR'));
    }

    /** @test */
    public function convertingBackAndForthMustHaveNoFailsInNumber(): void
    {
        $this->mockService();

        $rub = money('10000000', currency('rub'));
        $usd = $rub->convertInto(currency('usd'));

        $backRub = $usd->convertInto(currency('rub'));

        $this->assertTrue($rub->equals($backRub));
        $this->assertEquals('1 000 ₽', $backRub->toString());
        $this->assertTrue($rub->isSameCurrency($backRub));
        $this->assertEquals($rub->toString(), $backRub->toString());
    }

    /** @test */
    public function historicalConverting(): void
    {
        $this->mockService();

        $rub = money('10000000', currency('rub'));
        $usdNow = $rub->convertInto(currency('usd'));
        $usdHistorical = $rub->convertInto(currency('usd'), null, Carbon::createFromDate(2000, 12, 31));

        $this->assertNotEquals($usdNow->getPureAmount(), $usdHistorical->getPureAmount());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupConfig = Config::get('money');
        Config::set('money.service', 'exchangerate');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backupConfig);
    }
}
