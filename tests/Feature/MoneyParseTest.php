<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\NoCurrencyInParserStringException;
use PostScripton\Money\Exceptions\WrongParserStringException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyParseTest extends TestCase
{
    /** @test */
    public function parseMoneyWithDefaultValues()
    {
        $money = Money::parse('$ 1 234.5');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('1 234.5', $money->getAmount());

        $money = Money::parse('$ 123.4');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('123.4', $money->getAmount());

        $money = Money::parse('USD 123.4');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('123.4', $money->getAmount());

        $money = Money::parse('1 234.5 $');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('1 234.5', $money->getAmount());

        $money = Money::parse('1 234.5 USD');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('1 234.5', $money->getAmount());

        $money = Money::parse('$ -1 234.5');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('-1 234.5', $money->getAmount());

        $money = Money::parse('USD -1 234.5');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('-1 234.5', $money->getAmount());

        $money = Money::parse('$ 1 234.567890');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.6', $money->getAmount());
    }

    /** @test */
    public function parseMoneyWithACertainCurrency()
    {
        $money = Money::parse('$ 1 234.5', Currency::code('USD'));
        $this->assertEquals('USD', $money->getCurrency()->getCode());

        $money = Money::parse('USD 1 234.5', Currency::code('USD'));
        $this->assertEquals('USD', $money->getCurrency()->getCode());

        $money = Money::parse('₽ 1 234.5', Currency::code('RUB'));
        $this->assertEquals('RUB', $money->getCurrency()->getCode());

        $money = Money::parse('RUB 1 234.5', Currency::code('RUB'));
        $this->assertEquals('RUB', $money->getCurrency()->getCode());
    }

    /** @test */
    public function parseMoneyWithACertainThousandsSeparator()
    {
        $money = Money::parse("$ 1'234.5", null, '\'');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5', $money->getAmount());
    }

    /** @test */
    public function parseMoneyWithACertainDecimalSeparator()
    {
        $money = Money::parse("$ 1 234,5", null, null, ',');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5', $money->getAmount());
    }

    /** @test */
    public function parseMoneyWithPassedParameters()
    {
        $money = Money::parse("1.234,5 ₽", Currency::code('RUB'), '.', ',');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5', $money->getAmount());
        $this->assertEquals('₽', $money->getCurrency()->getSymbol());
        $this->assertEquals('RUB', $money->getCurrency()->getCode());
        $this->assertEquals(' ', $money->settings()->getThousandsSeparator());
        $this->assertEquals('.', $money->settings()->getDecimalSeparator());
    }

    /** @test */
    public function anExceptionIsThrownWhenThereIsNoCurrencyInTheGivenStringForParser()
    {
        $this->expectException(NoCurrencyInParserStringException::class);
        Money::parse('1 234.5');
    }

    /** @test */
    public function anExceptionIsThrownWhenThereIsAnErrorInTheGivenStringForParser()
    {
        $this->expectException(WrongParserStringException::class);
        Money::parse('qwerty 1234');
    }
}
