<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Tests\TestCase;

class NegativeMoneyTest extends TestCase
{
    public function testNegativeWithCurrencyStartAsSymbol(): void
    {
        $usd = money('-1234000', Currency::code('USD'));

        $this->assertEquals('$ -123.4', $usd->toString());
        $this->assertEquals('-1234000', $usd->getAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    public function testNegativeWithCurrencyStartAsSymbolNoSpace(): void
    {
        $usd = money('-1234000', Currency::code('USD'));

        $formatter = (new DefaultMoneyFormatter())
            ->spaceBetweenCurrencyAndAmount(false); // for symbol at front this is true anyway

        $this->assertEquals('$ -123.4', $usd->toString($formatter));
        $this->assertEquals('-1234000', $usd->getAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    public function testNegativeWithCurrencyStartAsCode(): void
    {
        $usd = money('-1234000', Currency::code('USD')->setDisplay(CurrencyDisplay::Code));

        $this->assertEquals('USD -123.4', $usd->toString());
        $this->assertEquals('-1234000', $usd->getAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    public function testNegativeWithCurrencyStartAsCodeNoSpace(): void
    {
        $usd = money('-1234000', Currency::code('USD')->setDisplay(CurrencyDisplay::Code));

        $formatter = (new DefaultMoneyFormatter())
            ->spaceBetweenCurrencyAndAmount(false); // for code display this is true anyway

        $this->assertEquals('USD -123.4', $usd->toString($formatter));
        $this->assertEquals('-1234000', $usd->getAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    public function testNegativeWithCurrencyEndAsSymbol(): void
    {
        $usd = money('-1234000', Currency::code('RUB'));

        $this->assertEquals('-123.4 ₽', $usd->toString());
        $this->assertEquals('-1234000', $usd->getAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    public function testNegativeWithCurrencyEndAsCode(): void
    {
        $usd = money('-1234000', Currency::code('RUB')->setDisplay(CurrencyDisplay::Code));

        $this->assertEquals('-123.4 RUB', $usd->toString());
        $this->assertEquals('-1234000', $usd->getAmount());
        $this->assertTrue($usd->isNegative());
        $this->assertNotTrue($usd->isPositive());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Currency::code('USD')
            ->setDisplay(CurrencyDisplay::Symbol)
            ->setPosition(CurrencyPosition::Start);
        Currency::code('RUB')
            ->setDisplay(CurrencyDisplay::Symbol)
            ->setPosition(CurrencyPosition::End);
    }
}
