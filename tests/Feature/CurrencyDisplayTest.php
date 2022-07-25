<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Tests\TestCase;

class CurrencyDisplayTest extends TestCase
{
    /** @test */
    public function displayCodeStart()
    {
        $currency = Currency::code('USD')
            ->setDisplay(Currency::DISPLAY_CODE);
        $usd = money('1234000', $currency);

        $this->assertEquals('USD 123.4', $usd->toString());
    }

    /** @test */
    public function displayCodeStartInEnd()
    {
        $currency = Currency::code('USD')
            ->setDisplay(Currency::DISPLAY_CODE)
            ->setPosition(Currency::POSITION_END);
        $usd = money('1234000', $currency);

        $this->assertEquals('123.4 USD', $usd->toString());
    }

    /** @test */
    public function displayCodeEnd()
    {
        $currency = Currency::code('RUB')
            ->setDisplay(Currency::DISPLAY_CODE);
        $usd = money('1234000', $currency);

        $this->assertEquals('123.4 RUB', $usd->toString());
    }

    /** @test */
    public function displayCodeEndInStart()
    {
        $currency = Currency::code('RUB')
            ->setDisplay(Currency::DISPLAY_CODE)
            ->setPosition(Currency::POSITION_START);
        $rub = money('1234000', $currency);

        $this->assertEquals('RUB 123.4', $rub->toString());
    }
}
