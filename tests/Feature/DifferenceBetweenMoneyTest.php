<?php

namespace PostScripton\Money\Tests;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;

class DifferenceBetweenMoneyTest extends TestCase
{
	private $backup_config;

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

	/** @test */
	public function difference_returns_a_string()
	{
		$m1 = money(500);
		$m2 = money(1000);

		$this->assertIsString($m1->difference($m2));
	}
	
	/** @test */
	public function difference_with_two_same_currencies()
	{
	    $m1 = money(500);
	    $m2 = money(1000);

	    $this->assertEquals(money(500)->subtract($m2)->toString(), $m1->difference($m2));
	}

	/** @test */
	public function difference_with_two_different_currencies()
	{
		$usd = money(500);
		$rub = money(1000, currency('rub'));
		$rub_into_usd = $rub->convertInto($usd->getCurrency());

		$this->assertEquals(money(500)->subtract($rub_into_usd)->toString(), $usd->difference($rub_into_usd));
	}

	/** @test */
	public function an_exception_is_thrown_when_there_are_two_different_currencies()
	{
		$m1 = money(500);
		$m2 = money(1000, currency('rub'));

		$this->expectException(MoneyHasDifferentCurrenciesException::class);

		$m1->difference($m2);
	}

	/** @test */
	public function new_settings_can_be_applied_to_the_difference()
	{
		$m1 = money(500);
		$m2 = money(1000);

		$this->assertEquals(
			money(500)->subtract($m2)->toString() . '.0',
			$m1->difference($m2, settings()->setEndsWith0(true))
		);
	}
}