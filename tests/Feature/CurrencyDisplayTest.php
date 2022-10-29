<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Tests\TestCase;

class CurrencyDisplayTest extends TestCase
{
    public function tearDown(): void
    {
        Currency::code('USD')
            ->setDisplay(CurrencyDisplay::Symbol)
            ->setPosition(CurrencyPosition::Start);
        Currency::code('RUB')
            ->setDisplay(CurrencyDisplay::Symbol)
            ->setPosition(CurrencyPosition::End);
    }

    /** @test */
    public function displayCodeStart(): void
    {
        $currency = Currency::code('USD')
            ->setDisplay(CurrencyDisplay::Code);
        $usd = money('1234000', $currency);

        $this->assertEquals('USD 123.4', $usd->toString());
    }

    /** @test */
    public function displayCodeStartInEnd(): void
    {
        $currency = Currency::code('USD')
            ->setDisplay(CurrencyDisplay::Code)
            ->setPosition(CurrencyPosition::End);
        $usd = money('1234000', $currency);

        $this->assertEquals('123.4 USD', $usd->toString());
    }

    /** @test */
    public function displayCodeEnd(): void
    {
        $currency = Currency::code('RUB')
            ->setDisplay(CurrencyDisplay::Code);
        $usd = money('1234000', $currency);

        $this->assertEquals('123.4 RUB', $usd->toString());
    }

    /** @test */
    public function displayCodeEndInStart(): void
    {
        $currency = Currency::code('RUB')
            ->setDisplay(CurrencyDisplay::Code)
            ->setPosition(CurrencyPosition::Start);
        $rub = money('1234000', $currency);

        $this->assertEquals('RUB 123.4', $rub->toString());
    }
}
