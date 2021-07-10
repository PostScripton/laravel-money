<?php

namespace PostScripton\Money\Tests\Feature\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\ServiceClassDoesNotExistException;
use PostScripton\Money\Exceptions\ServiceDoesNotHaveClassException;
use PostScripton\Money\Exceptions\ServiceDoesNotInheritServiceException;
use PostScripton\Money\Exceptions\ServiceRequestFailedException;
use PostScripton\Money\Services\ExchangeRatesAPIService;
use PostScripton\Money\Tests\TestCase;
use stdClass;

class ExchangeRatesAPITest extends TestCase
{
	private $backup_config;

	protected function setUp(): void
	{
		parent::setUp();
		$this->backup_config = Config::get('money');
		Currency::setCurrencyList(Currency::currentList());
		Config::set('money.service', 'exchangeratesapi');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		Config::set('money', $this->backup_config);
	}

	/** @test */
	public function getting_info_about_an_exchangeratesapi_service()
	{
		$money = money(1000);

		$this->assertInstanceOf(ExchangeRatesAPIService::class, $money->service());
		$this->assertEquals(ExchangeRatesAPIService::class, $money->service()->getClassName());
	}

	/** @test */
	public function an_incorrect_api_was_given_to_an_exchangeratesapi_service()
	{
		Config::set('money.services.' . config('money.service'),
			array_merge(config('money.services.' . config('money.service')), ['key' => 'incorrect_api_key'])
		);

		$this->expectException(ServiceRequestFailedException::class);

		$money = money(1000);
		$money->convertInto(currency('rub'));
	}

	/** @test */
	public function an_exchangeratesapi_service_does_not_have_class()
	{
		Config::set('money.services.' . config('money.service'),
			array_diff_key(config('money.services.' . config('money.service')), ['class' => ''])
		);

		$this->expectException(ServiceDoesNotHaveClassException::class);

		$money = money(1000);
		$money->convertInto(currency('rub'));
	}

	/** @test */
	public function an_exchangeratesapi_service_class_does_not_exist()
	{
		Config::set('money.services.' . config('money.service'),
			array_merge(config('money.services.' . config('money.service')), ['class' => 'incorrect_class'])
		);

		$this->expectException(ServiceClassDoesNotExistException::class);

		$money = money(1000);
		$money->convertInto(currency('rub'));
	}

	/** @test */
	public function an_exchangeratesapi_service_class_does_not_inherit_the_main_one()
	{
		Config::set('money.services.' . config('money.service'),
			array_merge(config('money.services.' . config('money.service')), ['class' => stdClass::class])
		);

		$this->expectException(ServiceDoesNotInheritServiceException::class);

		$money = money(1000);
		$money->convertInto(currency('rub'));
	}

	/** @test */
	public function exchangeratesapi_converting_back_and_forth_must_have_no_fails_in_number()
	{
		$rub = money(10000, currency('rub'));
		$usd = $rub->convertInto(currency('usd'));

		$back_rub = $usd->convertInto(currency('rub'));

		$this->assertFalse($rub->equals($back_rub));
		$this->assertEquals('1 000 ₽', $back_rub->toString());
		$this->assertTrue($rub->isSameCurrency($back_rub));
		$this->assertEquals($rub->getPureAmount(), $back_rub->getPureAmount());
	}

	/** @test */
	public function exchangeratesapi_historical_converting()
	{
		$rub = money(10000, currency('rub'));
		$usd_now = $rub->convertInto(currency('usd'));
		$usd_historical = $rub->convertInto(currency('usd'), null, Carbon::createFromDate(2010, 9, 8));

		$this->assertNotEquals($usd_now->getPureAmount(), $usd_historical->getPureAmount());
	}
}