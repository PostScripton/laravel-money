<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Tests\TestCase;

class MoneySettingsTest extends TestCase
{
    /** @test */
    public function noParams()
    {
        $settings = new MoneySettings();

        $this->assertEquals(1, $settings->getDecimals());
        $this->assertEquals(' ', $settings->getThousandsSeparator());
        $this->assertEquals('.', $settings->getDecimalSeparator());
        $this->assertFalse($settings->endsWith0());
        $this->assertTrue($settings->hasSpaceBetween());
        $this->assertInstanceOf(Currency::class, $settings->getCurrency());
        $this->assertEquals('$', $settings->getCurrency()->getSymbol());

        $this->assertEquals("$ 1 234.5", money('12345000', null, $settings)->toString());
    }

    /** @test */
    public function paramsThroughMethods()
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

        $this->assertEquals("1'234,56₽", money('12345600', null, $settings)->toString());
    }
}
