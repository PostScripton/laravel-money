<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Tests\TestCase;

class NegativeMoneyTest extends TestCase
{
    /** @test */
    public function negativeWithCurrencyStartAsSymbol()
    {
        $usd = money('-1234000', Currency::code('USD'));

        $this->assertEquals('$ -123.4', $usd->toString());
        $this->assertEquals('-1234000', $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function negativeWithCurrencyStartAsSymbolNoSpace()
    {
        $usd = money('-1234000', Currency::code('USD'));

        $usd->settings()->setHasSpaceBetween(false); // for symbol at front this is true anyway

        $this->assertEquals('$ -123.4', $usd->toString());
        $this->assertEquals('-1234000', $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function negativeWithCurrencyStartAsCode()
    {
        $usd = money('-1234000', Currency::code('USD')->setDisplay(CurrencyDisplay::Code));

        $this->assertEquals('USD -123.4', $usd->toString());
        $this->assertEquals('-1234000', $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function negativeWithCurrencyStartAsCodeNoSpace()
    {
        $usd = money('-1234000', Currency::code('USD')->setDisplay(CurrencyDisplay::Code));

        $usd->settings()->setHasSpaceBetween(false); // for DISPLAY_CODE this is true anyway

        $this->assertEquals('USD -123.4', $usd->toString());
        $this->assertEquals('-1234000', $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function negativeWithCurrencyEndAsSymbol()
    {
        $usd = money('-1234000', Currency::code('RUB'));

        $this->assertEquals('-123.4 ₽', $usd->toString());
        $this->assertEquals('-1234000', $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    /** @test */
    public function negativeWithCurrencyEndAsCode()
    {
        $usd = money('-1234000', Currency::code('RUB')->setDisplay(CurrencyDisplay::Code));

        $this->assertEquals('-123.4 RUB', $usd->toString());
        $this->assertEquals('-1234000', $usd->getPureAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }
}
