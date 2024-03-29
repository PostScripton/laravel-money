<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Enums\CurrencyPosition;
use PostScripton\Money\Tests\TestCase;

class CurrencyDisplayTest extends TestCase
{
    public function testDisplayCodeStart(): void
    {
        $currency = Currency::code('USD')
            ->setDisplay(CurrencyDisplay::Code);
        $usd = money('1234000', $currency);

        $this->assertEquals('USD 123.4', $usd->toString());
    }

    public function testDisplayCodeStartInEnd(): void
    {
        $currency = Currency::code('USD')
            ->setDisplay(CurrencyDisplay::Code)
            ->setPosition(CurrencyPosition::End);
        $usd = money('1234000', $currency);

        $this->assertEquals('123.4 USD', $usd->toString());
    }

    public function testDisplayCodeEnd(): void
    {
        $currency = Currency::code('RUB')
            ->setDisplay(CurrencyDisplay::Code);
        $usd = money('1234000', $currency);

        $this->assertEquals('123.4 RUB', $usd->toString());
    }

    public function testDisplayCodeEndInStart(): void
    {
        $currency = Currency::code('RUB')
            ->setDisplay(CurrencyDisplay::Code)
            ->setPosition(CurrencyPosition::Start);
        $rub = money('1234000', $currency);

        $this->assertEquals('RUB 123.4', $rub->toString());
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
