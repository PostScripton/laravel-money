<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\NoCurrencyInParserStringException;
use PostScripton\Money\Exceptions\WrongParserStringException;
use PostScripton\Money\Money;

class MoneyParseTest extends TestCase
{
    /** @test */
    public function parse_money_with_default_values()
    {
        $money = Money::parse('$ 1 234.5');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('1 234.5', $money->getNumber());

        $money = Money::parse('$ 123.4');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('123.4', $money->getNumber());

        $money = Money::parse('USD 123.4');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('123.4', $money->getNumber());

        $money = Money::parse('1 234.5 $');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('1 234.5', $money->getNumber());

        $money = Money::parse('1 234.5 USD');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('1 234.5', $money->getNumber());

        $money = Money::parse('$ -1 234.5');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('-1 234.5', $money->getNumber());

        $money = Money::parse('USD -1 234.5');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('$', $money->getCurrency()->getSymbol());
        $this->assertEquals('-1 234.5', $money->getNumber());

        $money = Money::parse('$ 1 234.567890');
        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.6', $money->getNumber());
    }
    
    /** @test */
    public function parse_money_with_a_certain_currency()
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
    public function parse_money_with_a_certain_thousands_separator()
    {
        $money = Money::parse("$ 1'234.5", null, '\'');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5', $money->getNumber());
    }

    /** @test */
    public function parse_money_with_a_certain_decimal_separator()
    {
        $money = Money::parse("$ 1 234,5", null, null, ',');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5', $money->getNumber());
    }

    /** @test */
    public function parse_money_with_passed_parameters()
    {
        $money = Money::parse("1.234,5 ₽", Currency::code('RUB'), '.', ',');

        $this->assertInstanceOf(Money::class, $money);
        $this->assertEquals('1 234.5', $money->getNumber());
        $this->assertEquals('₽', $money->getCurrency()->getSymbol());
        $this->assertEquals('RUB', $money->getCurrency()->getCode());
        $this->assertEquals(' ', $money->settings()->getThousandsSeparator());
        $this->assertEquals('.', $money->settings()->getDecimalSeparator());
    }
    
    /** @test */
    public function an_exception_is_thrown_when_there_is_no_currency_in_the_given_string_for_parser()
    {
        $this->expectException(NoCurrencyInParserStringException::class);
        Money::parse('1 234.5');
    }

    /** @test */
    public function an_exception_is_thrown_when_there_is_an_error_in_the_given_string_for_parser()
    {
        $this->expectException(WrongParserStringException::class);
        Money::parse('qwerty 1234');
    }
}