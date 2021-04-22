<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

class MoneySettingsTest extends TestCase
{
    /** @test */
    public function NoParams()
    {
        $settings = new MoneySettings();

        $this->assertEquals(1, $settings->getDecimals());
        $this->assertEquals(' ', $settings->getThousandsSeparator());
        $this->assertEquals('.', $settings->getDecimalSeparator());
        $this->assertFalse($settings->endsWith0());
        $this->assertTrue($settings->hasSpaceBetween());
        $this->assertInstanceOf(Currency::class, $settings->getCurrency());
        $this->assertEquals('$', $settings->getCurrency()->getSymbol());

        $this->assertEquals("$ 1 234.5", strval(Money::make(12345, null, $settings)));
    }

    /** @test */
    public function ParamsThroughMethods()
    {
        $settings = (new MoneySettings())
            ->setDecimals(2)
            ->setThousandsSeparator('\'')
            ->setDecimalSeparator(',')
            ->setEndsWith0(true)
            ->setHasSpaceBetween(false)
            ->setCurrency(Currency::code('RUB'));

        $this->assertEquals(2, $settings->getDecimals());
        $this->assertEquals('\'', $settings->getThousandsSeparator());
        $this->assertEquals(',', $settings->getDecimalSeparator());
        $this->assertTrue($settings->endsWith0());
        $this->assertFalse($settings->hasSpaceBetween());
        $this->assertInstanceOf(Currency::class, $settings->getCurrency());
        $this->assertEquals('₽', $settings->getCurrency()->getSymbol());

        $this->assertEquals("1'234,56₽", strval(Money::make(123456, null, $settings)));
    }
}