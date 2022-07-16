<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\WrongParserStringException;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyParseTest extends TestCase
{
    /** @test */
    public function parseMoneyWithDifferentThousands()
    {
        $m1 = Money::parse('$ 1 234');
        $m2 = Money::parse('$ 1.234');
        $m3 = Money::parse('$ 1,234');
        $m4 = Money::parse('$ 1\'234');

        $this->assertEquals('12340000', $m1->getPureAmount());
        $this->assertEquals('12340000', $m2->getPureAmount());
        $this->assertEquals('12340000', $m3->getPureAmount());
        $this->assertEquals('12340000', $m4->getPureAmount());
    }

    /** @test */
    public function parseMoneyWithDifferentDecimals()
    {
        $m1 = Money::parse('$ 0.5');
        $m2 = Money::parse('$ 0,5');

        $this->assertEquals('5000', $m1->getPureAmount());
        $this->assertEquals('5000', $m2->getPureAmount());
    }

    /** @test */
    public function parseMoneyWithoutThousandsAndDecimals()
    {
        $money = Money::parse('$ 123');

        $this->assertEquals('1230000', $money->getPureAmount());
    }

    /** @test */
    public function parseMoneyWithNegativeAmount()
    {
        $m1 = Money::parse('$ -123');
        $m2 = Money::parse('-123$');
        $m3 = Money::parse('-123 $');

        $this->assertEquals('-1230000', $m1->getPureAmount());
        $this->assertEquals('-1230000', $m2->getPureAmount());
        $this->assertEquals('-1230000', $m3->getPureAmount());
    }

    /** @test */
    public function parseMoneyWithCurrenciesAtTheBeginning()
    {
        $usd1 = Money::parse('$ 100');
        $usd2 = Money::parse('$100');
        $usd3 = Money::parse('USD 100');
        $rub1 = Money::parse('₽ 100');
        $rub2 = Money::parse('₽100');
        $rub3 = Money::parse('RUB 100');

        $this->assertEquals('1000000', $usd1->getPureAmount());
        $this->assertEquals('$', $usd1->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $usd2->getPureAmount());
        $this->assertEquals('$', $usd2->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $usd3->getPureAmount());
        $this->assertEquals('USD', $usd3->getCurrency()->getCode());
        $this->assertEquals('1000000', $rub1->getPureAmount());
        $this->assertEquals('₽', $rub1->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $rub2->getPureAmount());
        $this->assertEquals('₽', $rub2->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $rub3->getPureAmount());
        $this->assertEquals('RUB', $rub3->getCurrency()->getCode());
    }

    /** @test */
    public function parseMoneyWithCurrenciesAtTheEnd()
    {
        $usd1 = Money::parse('100 $');
        $usd2 = Money::parse('100$');
        $usd3 = Money::parse('100 USD');
        $rub1 = Money::parse('100 ₽');
        $rub2 = Money::parse('100₽');
        $rub3 = Money::parse('100 RUB');

        $this->assertEquals('1000000', $usd1->getPureAmount());
        $this->assertEquals('$', $usd1->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $usd2->getPureAmount());
        $this->assertEquals('$', $usd2->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $usd3->getPureAmount());
        $this->assertEquals('USD', $usd3->getCurrency()->getCode());
        $this->assertEquals('1000000', $rub1->getPureAmount());
        $this->assertEquals('₽', $rub1->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $rub2->getPureAmount());
        $this->assertEquals('₽', $rub2->getCurrency()->getSymbol());
        $this->assertEquals('1000000', $rub3->getPureAmount());
        $this->assertEquals('RUB', $rub3->getCurrency()->getCode());
    }

    /** @test */
    public function unknownCurrencyIsParsedAsDefaultOne()
    {
        $m1 = Money::parse('# 100');
        $m2 = Money::parse('100 #');

        $this->assertInstanceOf(Currency::class, $m1->getCurrency());
        $this->assertEquals('USD', $m1->getCurrency()->getCode());
        $this->assertInstanceOf(Currency::class, $m2->getCurrency());
        $this->assertEquals('USD', $m2->getCurrency()->getCode());
    }

    /** @test */
    public function anExceptionIsThrownWhenThereIsAnErrorInTheGivenStringForParser()
    {
        $this->expectException(WrongParserStringException::class);
        Money::parse('qwerty');
    }
}
