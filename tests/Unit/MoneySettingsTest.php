<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Tests\TestCase;

class MoneySettingsTest extends TestCase
{
    /** @test */
    public function noParams(): void
    {
        $settings = new MoneySettings();

        $this->assertEquals(1, $settings->getDecimals());
        $this->assertEquals(' ', $settings->getThousandsSeparator());
        $this->assertEquals('.', $settings->getDecimalSeparator());
        $this->assertFalse($settings->endsWith0());
        $this->assertTrue($settings->hasSpaceBetween());

        $this->assertEquals("$ 1 234.5", money('12345000', null, $settings)->toString());
    }

    /** @test */
    public function paramsThroughMethods(): void
    {
        $settings = (new MoneySettings())
            ->setDecimals(2)
            ->setThousandsSeparator('\'')
            ->setDecimalSeparator(',')
            ->setEndsWith0(true)
            ->setHasSpaceBetween(false);

        $this->assertEquals(2, $settings->getDecimals());
        $this->assertEquals('\'', $settings->getThousandsSeparator());
        $this->assertEquals(',', $settings->getDecimalSeparator());
        $this->assertTrue($settings->endsWith0());
        $this->assertFalse($settings->hasSpaceBetween());

        $this->assertEquals("1'234,56₽", money('12345600', Currency::code('RUB'), $settings)->toString());
    }
}
