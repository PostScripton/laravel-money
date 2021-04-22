<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Exceptions\UndefinedOriginException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

class FloatOriginTest extends TestCase
{
    /** @test
     * @throws UndefinedOriginException
     */
    public function FloatOrigin()
    {
        $settings = new MoneySettings();
        $settings->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $money = Money::make(1234.56, null, $settings);

        $this->assertEquals('1 234.6', $money->getNumber());
        $this->assertEquals(1234.56, $money->getPureNumber());
        $this->assertEquals('$ 1 234.6', $money->toString());
        $this->assertEquals(12345, $money->toInteger());
    }

    /** @test
     * @throws UndefinedOriginException
     * @throws CurrencyDoesNotExistException
     */
    public function FloatOriginAfterConverting()
    {
        $settings = new MoneySettings();
        $settings->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $coeff = 75.32;
        $rub = Money::make(1000, Currency::code('RUB'), $settings);
        $usd = $rub->convertOfflineInto(Currency::code('USD'), 1 / $coeff);

        $usd->settings->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $this->assertEquals('13.3', $usd->getNumber());
        $this->assertEquals(13.276686139139672, $usd->getPureNumber());
        $this->assertEquals('$ 13.3', $usd->toString());
        $this->assertEquals(132, $usd->toInteger());
    }
}