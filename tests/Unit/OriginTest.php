<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Tests\TestCase;

class OriginTest extends TestCase
{
    /** @test */
    public function moneyWithOriginInt()
    {
        $settings = new MoneySettings();
        $settings->setOrigin(MoneySettings::ORIGIN_INT);

        $money = new Money(132.76686139139672, $settings);

        $this->assertEquals('13.3', $money->getAmount());
        $this->assertEquals(132.76686139139672, $money->getPureAmount());
        $this->assertEquals(132, $money->upload());
    }

    /** @test */
    public function moneyWithOriginFloat()
    {
        $settings = new MoneySettings();
        $settings->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $money = new Money(13.276686139139672, $settings);

        $this->assertEquals('13.3', $money->getAmount());
        $this->assertEquals(13.276686139139672, $money->getPureAmount());
        $this->assertEquals(13.2, $money->upload());
    }

    /** @test */
    public function setOriginFloatForMoneyWithOriginInt()
    {
        $settings = new MoneySettings();
        $settings->setOrigin(MoneySettings::ORIGIN_INT);

        $money = new Money(132.76686139139672, $settings);
        $this->assertEquals(132.76686139139672, $money->getPureAmount());

        $money->settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $this->assertEquals('13.3', $money->getAmount());
        $this->assertEquals(13.276686139139672, $money->getPureAmount());
        $this->assertEquals(13.2, $money->upload());
    }

    /** @test */
    public function setOriginIntForMoneyWithOriginFloat()
    {
        $settings = new MoneySettings();
        $settings->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $money = new Money(13.276686139139672, $settings);
        $this->assertEquals(13.276686139139672, $money->getPureAmount());

        $money->settings()->setOrigin(MoneySettings::ORIGIN_INT);

        $this->assertEquals('13.3', $money->getAmount());
        $this->assertEquals(132.76686139139672, $money->getPureAmount());
        $this->assertEquals(132, $money->upload());
    }
}
