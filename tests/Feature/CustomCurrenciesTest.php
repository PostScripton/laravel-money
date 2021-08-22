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

    /** @test */
    public function useACustomCurrency()
    {
        Config::set('money.custom_currencies', [
            $this->customCurrency(),
        ]);

        Currency::setCurrencyList(Currency::LIST_CONFIG);

        $btc = currency('XBT');
        $this->assertInstanceOf(Currency::class, $btc);
        $this->assertEquals('XBT', $btc->getCode());
        $this->assertEquals('1234', $btc->getNumCode());
    }

    /** @test */
    public function aCustomCurrencyHasIsoCodeOfTheExistingOne()
    {
        $this->expectException(CustomCurrencyTakenCodesException::class);

        Config::set('money.custom_currencies', [
            array_merge($this->customCurrency(), ['iso_code' => 'usd']),
        ]);

        Currency::setCurrencyList(Currency::LIST_CONFIG);
    }

    /** @test */
    public function aCustomCurrencyHasNumCodeOfTheExistingOne()
    {
        $this->expectException(CustomCurrencyTakenCodesException::class);

        Config::set('money.custom_currencies', [
            array_merge($this->customCurrency(), ['num_code' => '840']),
        ]);

        Currency::setCurrencyList(Currency::LIST_CONFIG);
    }

    /** @test */
    public function anExceptionIsThrownWhenThereAreTwoCustomCurrenciesWithTheSameCodes()
    {
        $this->expectException(CustomCurrencyTakenCodesException::class);

        Config::set('money.custom_currencies', [
            $this->customCurrency(),
            $this->customCurrency(),
        ]);

        Currency::setCurrencyList(Currency::LIST_CONFIG);
    }

    /** @test */
    public function aCustomCurrencyDoesNotHaveARequiredField()
    {
        $this->expectException(CustomCurrencyDoesNotHaveFieldException::class);

        Config::set('money.custom_currencies', [
            array_diff_key($this->customCurrency(), ['full_name' => '']),
        ]);

        Currency::setCurrencyList(Currency::LIST_CONFIG);
    }

    /** @test */
    public function aCustomCurrencyHasAFieldWithAWrongType()
    {
        $this->expectException(CustomCurrencyWrongFieldTypeException::class);

        Config::set('money.custom_currencies', [
            array_merge($this->customCurrency(), ['full_name' => false]),
        ]);

        Currency::setCurrencyList(Currency::LIST_CONFIG);
    }

    /** @test */
    public function aConfigPropertyCustomCurrenciesMustBeAnArray()
    {
        $this->expectException(BaseException::class);
        $this->expectExceptionMessage('The config property "custom_currencies" must be an array.');

        Config::set('money.custom_currencies', true);

        Currency::setCurrencyList(Currency::LIST_CONFIG);
    }

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
