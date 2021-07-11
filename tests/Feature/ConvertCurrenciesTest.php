<?php

namespace PostScripton\Money\Tests\Feature;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\ServiceDoesNotSupportCurrencyException;
use PostScripton\Money\Tests\TestCase;

class ConvertCurrenciesTest extends TestCase
{
    private $backup_config;

    /** @test */
    public function moneyCanBeOfflineConvertedBetweenTwoCurrenciesWithoutFailsInNumber()
    {
        $rate = 75.32;
        $rub = money(10000, currency('RUB'));
        $this->assertEquals('1 000 ₽', $rub->toString());

        $usd = $rub->convertInto(currency('USD'), 1 / $rate);
        $this->assertEquals('$ 13.3', $usd->toString());

        $back_rub = $usd->convertInto(currency('RUB'), $rate / 1);
        $this->assertEquals('1 000 ₽', $back_rub->toString());

        $this->assertFalse($rub->equals($back_rub));
        $this->assertTrue($rub->isSameCurrency($back_rub));
        $this->assertEquals($rub->getPureAmount(), $back_rub->getPureAmount());
    }

    /** @test */
    public function theGivenCurrencyIsNotSupportedForConvertingByAService()
    {
        Config::set('money.custom_currencies', [
            [
                'full_name' => 'QWERTY',
                'name' => 'QWERTY',
                'iso_code' => 'QWERTY',
                'num_code' => '1234',
                'symbol' => 'QWERTY',
                'position' => Currency::POSITION_START,
            ],
        ]);
        Currency::setCurrencyList(Currency::currentList());

        $this->expectException(ServiceDoesNotSupportCurrencyException::class);

        $money = money(1000);
        $money->convertInto(currency('1234'));
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
