<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Tests\TestCase;

class UploadTest extends TestCase
{
    /** @test */
    public function uploadTheNumberOfTheOriginIntMoney()
    {
        $money = Money::make(12345.67890);

        $this->assertEquals(12345, $money->upload());
        $this->assertEquals(12345.67890, $money->getPureAmount());
    }

    /** @test */
    public function uploadTheNumberOfTheOriginFloatMoney()
    {
        $money = Money::make(1234.567890);
        $money->settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $this->assertEquals(1234.5, $money->upload());
        $this->assertEquals(1234.567890, $money->getPureAmount());
    }
}
