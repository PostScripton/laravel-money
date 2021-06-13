<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;

class NegativeMoneyTest extends TestCase
{
    /** @test */
    public function NegativeWithCurrencyStartAsSymbol()
    {
        $usd = new Money(-1234, Currency::code('USD'));

        $this->assertEquals('$ -123.4', $usd->toString());
        $this->assertEquals(-1234, $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function NegativeWithCurrencyStartAsSymbolNoSpace()
    {
        $usd = new Money(-1234, Currency::code('USD'));

        $usd->settings()->setHasSpaceBetween(false); // for symbol at front this is true anyway

        $this->assertEquals('$ -123.4', $usd->toString());
        $this->assertEquals(-1234, $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function NegativeWithCurrencyStartAsCode()
    {
        $usd = new Money(-1234, Currency::code('USD')->setDisplay(Currency::DISPLAY_CODE));

        $this->assertEquals('USD -123.4', $usd->toString());
        $this->assertEquals(-1234, $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function NegativeWithCurrencyStartAsCodeNoSpace()
    {
        $usd = new Money(-1234, Currency::code('USD')->setDisplay(Currency::DISPLAY_CODE));

        $usd->settings()->setHasSpaceBetween(false); // for DISPLAY_CODE this is true anyway

        $this->assertEquals('USD -123.4', $usd->toString());
        $this->assertEquals(-1234, $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function NegativeWithCurrencyEndAsSymbol()
    {
        $usd = new Money(-1234, Currency::code('RUB'));

        $this->assertEquals('-123.4 ₽', $usd->toString());
        $this->assertEquals(-1234, $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function NegativeWithCurrencyEndAsCode()
    {
        $usd = new Money(-1234, Currency::code('RUB')->setDisplay(Currency::DISPLAY_CODE));

        $this->assertEquals('-123.4 RUB', $usd->toString());
        $this->assertEquals(-1234, $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }
}