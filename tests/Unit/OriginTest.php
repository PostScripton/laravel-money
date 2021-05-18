<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

class OriginTest extends TestCase
{
    /** @test */
    public function money_with_origin_int()
    {
        $settings = new MoneySettings;
        $settings->setOrigin(MoneySettings::ORIGIN_INT);

        $money = new Money(132.76686139139672, $settings);

        $this->assertEquals('13.3', $money->getNumber());
        $this->assertEquals(132.76686139139672, $money->getPureNumber());
        $this->assertEquals(132, $money->upload());
    }

    /** @test */
    public function money_with_origin_float()
    {
        $settings = new MoneySettings;
        $settings->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $money = new Money(13.276686139139672, $settings);

        $this->assertEquals('13.3', $money->getNumber());
        $this->assertEquals(13.276686139139672, $money->getPureNumber());
        $this->assertEquals(13.2, $money->upload());
    }

    /** @test */
    public function set_origin_float_for_money_with_origin_int()
    {
        $settings = new MoneySettings;
        $settings->setOrigin(MoneySettings::ORIGIN_INT);

        $money = new Money(132.76686139139672, $settings);
        $this->assertEquals(132.76686139139672, $money->getPureNumber());

        $money->settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $this->assertEquals('13.3', $money->getNumber());
        $this->assertEquals(13.276686139139672, $money->getPureNumber());
        $this->assertEquals(13.2, $money->upload());
    }

    /** @test */
    public function set_origin_int_for_money_with_origin_float()
    {
        $settings = new MoneySettings;
        $settings->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $money = new Money(13.276686139139672, $settings);
        $this->assertEquals(13.276686139139672, $money->getPureNumber());

        $money->settings()->setOrigin(MoneySettings::ORIGIN_INT);

        $this->assertEquals('13.3', $money->getNumber());
        $this->assertEquals(132.76686139139672, $money->getPureNumber());
        $this->assertEquals(132, $money->upload());
    }
}
