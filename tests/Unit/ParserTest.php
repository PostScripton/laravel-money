<?php

namespace PostScripton\Money\Tests\Unit;

use Exception;
use PostScripton\Money\Parser;
use PostScripton\Money\Tests\TestCase;

class ParserTest extends TestCase
{
    public function moneyStringsDataProvider(): array
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

    /** @dataProvider moneyStringsDataProvider */
    public function testParse(string $money, string $pureAmount, ?string $currencyCode = null): void
    {
        $expectedCurrencyCode = $currencyCode ?? 'USD';
        $money = Parser::parse($money, $currencyCode);

        $this->assertEquals($pureAmount, $money->getPureAmount());
        $this->assertEquals($expectedCurrencyCode, $money->getCurrency()->getCode());
    }

    public function wrongMoneyStringsDataProvider(): array
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

    /** @dataProvider wrongMoneyStringsDataProvider */
    public function testParserThrowsAnExceptionWhenWrongStringIsPassed(
        string $money,
        ?string $currencyCode = null,
    ): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unable to parse [{$money}] into a monetary object");

        Parser::parse($money, $currencyCode);
    }
}
