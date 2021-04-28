<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Money;

class NumberFromMoneyTest extends TestCase
{
    /** @test */
    public function GetNumberFromMoney()
    {
        $money = Money::make(12345);

        $this->assertEquals('1 234.5', $money->getNumber());
        $this->assertEquals(12345.0, $money->getPureNumber());
    }
}