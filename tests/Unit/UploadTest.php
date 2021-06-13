<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

class UploadTest extends TestCase
{
    /** @test */
    public function upload_the_number_of_the_origin_int_money()
    {
        $money = Money::make(12345.67890);

        $this->assertEquals(12345, $money->upload());
        $this->assertEquals(12345.67890, $money->getPureAmount());
    }

    /** @test */
    public function upload_the_number_of_the_origin_float_money()
    {
        $money = Money::make(1234.567890);
        $money->settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

        $this->assertEquals(1234.5, $money->upload());
        $this->assertEquals(1234.567890, $money->getPureAmount());
    }
}