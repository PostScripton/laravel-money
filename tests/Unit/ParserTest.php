<?php

namespace PostScripton\Money\Tests\Unit;

use Exception;
use PostScripton\Money\Currency;
use PostScripton\Money\Parser;
use PostScripton\Money\Tests\TestCase;

class ParserTest extends TestCase
{
    /** @dataProvider moneyStringsDataProvider */
    public function testParse(string $money, string $pureAmount, ?string $currencyCode = null): void
    {
        $expectedCurrencyCode = Currency::getOrDefault($currencyCode)->getCode();
        $money = Parser::parse($money, $currencyCode);

        $this->assertEquals($pureAmount, $money->getAmount());
        $this->assertEquals($expectedCurrencyCode, $money->getCurrency()->getCode());
    }

    /** @dataProvider wrongMoneyStringsDataProvider */
    public function testParserThrowsAnExceptionWhenWrongStringIsPassed(
        string $money,
        ?string $currencyCode = null,
    ): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unable to parse [{$money}] into a monetary object");

        Parser::parse($money, $currencyCode);
    }

    public function testParseWithVariousWaysToPassCurrencies(): void
    {
        $m1 = money_parse('100', 'RUB');
        $m2 = money_parse('100', currency('RUB'));
        $m3 = money_parse('100');

        $this->assertEquals('RUB', $m1->getCurrency()->getCode());
        $this->assertEquals('RUB', $m2->getCurrency()->getCode());
        $this->assertEquals('USD', $m3->getCurrency()->getCode());
    }

    protected function moneyStringsDataProvider(): array
    {
        return [
            [
                'money' => '1234',
                'pureAmount' => '12340000',
            ],
            [
                'money' => '1 234',
                'pureAmount' => '12340000',
            ],
            [
                'money' => '-1234',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '-1 234',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '1234.5678',
                'pureAmount' => '12345678',
            ],
            [
                'money' => '1 234.5678',
                'pureAmount' => '12345678',
            ],
            [
                'money' => '-1234.5678',
                'pureAmount' => '-12345678',
            ],
            [
                'money' => '-1 234.5678',
                'pureAmount' => '-12345678',
            ],
            [
                'money' => '0.0001',
                'pureAmount' => '1',
            ],
            [
                'money' => '-0.0001',
                'pureAmount' => '-1',
            ],
            [
                'money' => '0',
                'pureAmount' => '0',
            ],
            [
                'money' => '-0',
                'pureAmount' => '0',
            ],
            [
                'money' => '$ 1234',
                'pureAmount' => '12340000',
            ],
            [
                'money' => '$ -1234',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '$1234',
                'pureAmount' => '12340000',
            ],
            [
                'money' => '$-1234',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => 'USD 1234',
                'pureAmount' => '12340000',
            ],
            [
                'money' => 'USD -1234',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => 'USD1234',
                'pureAmount' => '12340000',
            ],
            [
                'money' => 'USD-1234',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '1234 USD',
                'pureAmount' => '12340000',
            ],
            [
                'money' => '-1234 USD',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '1234USD',
                'pureAmount' => '12340000',
            ],
            [
                'money' => '-1234USD',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '1234 ₽',
                'pureAmount' => '12340000',
                'currencyCode' => 'RUB',
            ],
            [
                'money' => '-1234 ₽',
                'pureAmount' => '-12340000',
                'currencyCode' => 'RUB',
            ],
            // anomaly cases
            [
                'money' => '$1234$',
                'pureAmount' => '12340000',
            ],
            [
                'money' => 'USD-1234$',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '$1234USD',
                'pureAmount' => '12340000',
            ],
            [
                'money' => 'USD-1234USD',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '$ 1234 $',
                'pureAmount' => '12340000',
            ],
            [
                'money' => 'USD -1234 $',
                'pureAmount' => '-12340000',
            ],
            [
                'money' => '$ 1234 USD',
                'pureAmount' => '12340000',
            ],
            [
                'money' => 'USD -1234 USD',
                'pureAmount' => '-12340000',
            ],
        ];
    }

    protected function wrongMoneyStringsDataProvider(): array
    {
        return [
            [
                'money' => '',
            ],
            [
                'money' => '0.12345',
            ],
            [
                'money' => '-$100',
            ],
            [
                'money' => 'US$ 100',
            ],
            [
                'money' => 'US$ -100',
            ],
            [
                'money' => '-USD 100',
            ],
            [
                'money' => '-USD100',
            ],
            [
                'money' => '100 ₽',
                'currencyCode' => 'USD',
            ],
            [
                'money' => '100 $',
                'currencyCode' => 'RUB',
            ],
            [
                'money' => '100 RUB',
                'currencyCode' => 'USD',
            ],
            [
                'money' => '100 USD',
                'currencyCode' => 'RUB',
            ],
            [
                'money' => '₽ 100 $',
                'currencyCode' => 'USD',
            ],
            [
                'money' => '$ 100 ₽',
                'currencyCode' => 'USD',
            ],
        ];
    }
}
