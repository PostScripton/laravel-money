<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Tests\TestCase;

class ConvertCurrenciesTest extends TestCase
{
    private $backup_config;

    /** @test */
    public function moneyCanBeOfflineConvertedBetweenTwoCurrenciesWithoutFailsInNumber()
    {
        $rate = 75.32;
        $rub = money('10000000', currency('RUB'));
        $this->assertEquals('1 000 ₽', $rub->toString());

        $usd = $rub->convertInto(currency('USD'), 1 / $rate);
        $this->assertEquals('$ 13.3', $usd->toString());

        $back_rub = $usd->convertInto(currency('RUB'), $rate / 1);
        $this->assertEquals('1 000 ₽', $back_rub->toString());

        $this->assertFalse($rub->equals($back_rub));
        $this->assertTrue($rub->isSameCurrency($back_rub));
        $this->assertEquals($rub->getPureAmount(), $back_rub->getPureAmount());
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
