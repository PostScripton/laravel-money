<?php

namespace PostScripton\Money\Tests\Feature;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\BaseException;
use PostScripton\Money\Exceptions\CustomCurrencyDoesNotHaveFieldException;
use PostScripton\Money\Exceptions\CustomCurrencyTakenCodesException;
use PostScripton\Money\Exceptions\CustomCurrencyWrongFieldTypeException;
use PostScripton\Money\Tests\TestCase;

class CustomCurrenciesTest extends TestCase
{
	private $backup_config;

	protected function setUp(): void
	{
		parent::setUp();
		$this->backup_config = Config::get('money');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		Config::set('money', $this->backup_config);
	}

	/** @test */
	public function use_a_custom_currency()
	{
		Config::set('money.custom_currencies', [
			$this->customCurrency()
		]);

		Currency::setCurrencyList(Currency::LIST_CONFIG);

		$btc = currency('XBT');
		$this->assertInstanceOf(Currency::class, $btc);
		$this->assertEquals('XBT', $btc->getCode());
		$this->assertEquals('1234', $btc->getNumCode());
	}

	/** @test */
	public function a_custom_currency_has_iso_code_of_the_existing_one()
	{
		$this->expectException(CustomCurrencyTakenCodesException::class);

		Config::set('money.custom_currencies', [
			array_merge($this->customCurrency(), ['iso_code' => 'usd'])
		]);

		Currency::setCurrencyList(Currency::LIST_CONFIG);
	}

	/** @test */
	public function a_custom_currency_has_num_code_of_the_existing_one()
	{
		$this->expectException(CustomCurrencyTakenCodesException::class);

		Config::set('money.custom_currencies', [
			array_merge($this->customCurrency(), ['num_code' => '840'])
		]);

		Currency::setCurrencyList(Currency::LIST_CONFIG);
	}

	/** @test */
	public function an_exception_is_thrown_when_there_are_two_custom_currencies_with_the_same_codes()
	{
		$this->expectException(CustomCurrencyTakenCodesException::class);

		Config::set('money.custom_currencies', [
			$this->customCurrency(),
			$this->customCurrency(),
		]);

		Currency::setCurrencyList(Currency::LIST_CONFIG);
	}
	
	/** @test */
	public function a_custom_currency_does_not_have_a_required_field()
	{
	    $this->expectException(CustomCurrencyDoesNotHaveFieldException::class);

	    Config::set('money.custom_currencies', [
			array_diff_key($this->customCurrency(), ['full_name' => ''])
		]);

		Currency::setCurrencyList(Currency::LIST_CONFIG);
	}

	/** @test */
	public function a_custom_currency_has_a_field_with_a_wrong_type()
	{
		$this->expectException(CustomCurrencyWrongFieldTypeException::class);

		Config::set('money.custom_currencies', [
			array_merge($this->customCurrency(), ['full_name' => false])
		]);

		Currency::setCurrencyList(Currency::LIST_CONFIG);
	}

	/** @test */
	public function a_config_property_custom_currencies_must_be_an_array()
	{
		$this->expectException(BaseException::class);
		$this->expectExceptionMessage('The config property "custom_currencies" must be an array.');

		Config::set('money.custom_currencies', true);

		Currency::setCurrencyList(Currency::LIST_CONFIG);
	}

	private function customCurrency(): array
	{
		return [
			'full_name' => 'Bitcoin',
			'name' => 'BTC',
			'iso_code' => 'XBT',
			'num_code' => '1234',
			'symbol' => '₿',
			'position' => Currency::POSITION_START,
		];
	}
}