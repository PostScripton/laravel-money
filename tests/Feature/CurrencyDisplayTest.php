<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;

class CurrencyDisplayTest extends TestCase
{
    /** @test */
    public function DisplayCodeStart()
    {
        $currency = Currency::code('USD')
            ->setDisplay(Currency::DISPLAY_CODE);
        $usd = Money::make(1234, $currency);

        $this->assertEquals('USD 123.4', $usd->toString());
    }

    /** @test */
    public function DisplayCodeStartInEnd()
    {
        $currency = Currency::code('USD')
            ->setDisplay(Currency::DISPLAY_CODE)
            ->setPosition(Currency::POS_END);
        $usd = Money::make(1234, $currency);

        $this->assertEquals('123.4 USD', $usd->toString());
    }

    /** @test */
    public function DisplayCodeEnd()
    {
        $currency = Currency::code('RUB')
            ->setDisplay(Currency::DISPLAY_CODE);
        $usd = Money::make(1234, $currency);

        $this->assertEquals('123.4 RUB', $usd->toString());
    }

    /** @test */
    public function DisplayCodeEndInStart()
    {
        $currency = Currency::code('RUB')
            ->setDisplay(Currency::DISPLAY_CODE)
            ->setPosition(Currency::POS_START);
        $rub = Money::make(1234, $currency);

        $this->assertEquals('RUB 123.4', $rub->toString());
    }
}