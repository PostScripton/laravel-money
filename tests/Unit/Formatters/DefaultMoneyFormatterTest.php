<?php

namespace PostScripton\Money\Tests\Unit\Formatters;

use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Tests\TestCase;

class DefaultMoneyFormatterTest extends TestCase
{
    /** @dataProvider formatterDataProvider */
    public function testFormat(array $methods, string $result): void
    {
        $money = money_parse('1 234.56');

        $formatter = (new DefaultMoneyFormatter());
        foreach ($methods as $method => $arguments) {
            $arguments = array_filter((array) $arguments, fn(mixed $arg) => ! is_null($arg));
            $formatter->{$method}(...$arguments);
        }

        $this->assertEquals($result, $formatter->format($money));
    }

    protected function formatterDataProvider(): array
    {
        return [
            [
                'methods' => [],
                'result' => '$ 1 234.6',
            ],
            [
                'methods' => [
                    'thousandsSeparator' => '.',
                    'decimalSeparator' => ',',
                    'decimals' => 4,
                    'endsWithZero' => false,
                ],
                'result' => '$ 1.234,56',
            ],
            [
                'methods' => [
                    'thousandsSeparator' => '.',
                    'decimalSeparator' => ',',
                    'decimals' => 4,
                    'endsWithZero' => true,
                ],
                'result' => '$ 1.234,5600',
            ],
            [
                'methods' => [
                    'decimals' => 0,
                ],
                'result' => '$ 1 235',
            ],
            [
                'methods' => [
                    'dontUseCurrency' => null,
                ],
                'result' => '1 234.6',
            ],
            [
                'methods' => [
                    'useCurrency' => null,
                    'displayCurrencyAs' => [CurrencyDisplay::Code],
                ],
                'result' => 'USD 1 234.6',
            ],
            [
                'methods' => [
                    'useCurrency' => null,
                    'displayCurrencyAs' => [CurrencyDisplay::Code],
                    'spaceBetweenCurrencyAndAmount' => false,
                ],
                'result' => 'USD 1 234.6',
            ],
            [
                'methods' => [
                    'useCurrency' => null,
                    'displayCurrencyAs' => [CurrencyDisplay::Symbol],
                    'spaceBetweenCurrencyAndAmount' => false,
                ],
                'result' => '$1 234.6',
            ],
        ];
    }
}
