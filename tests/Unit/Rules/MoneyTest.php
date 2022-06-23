<?php

namespace PostScripton\Money\Tests\Unit\Rules;

use PostScripton\Money\Rules\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyTest extends TestCase
{
    public function inputsDataProvider(): array
    {
        return [
            [
                'input' => '1500',
                'result' => true,
            ],
            [
                'input' => '1 500',
                'result' => true,
            ],
            [
                'input' => '1500.0',
                'result' => true,
            ],
            [
                'input' => '1 500.0',
                'result' => true,
            ],
            [
                'input' => '0',
                'result' => true,
            ],
            [
                'input' => '0.0',
                'result' => true,
            ],
            [
                'input' => '0.2500',
                'result' => true,
            ],
            [
                'input' => '0.25001',
                'result' => false,
            ],
            [
                'input' => '01500.0',
                'result' => false,
            ],
            [
                'input' => '01 500.0',
                'result' => false,
            ],
            [
                'input' => ' 500.0',
                'result' => false,
            ],
            [
                'input' => '150 0.0',
                'result' => false,
            ],
            [
                'input' => '15 00.0',
                'result' => false,
            ],
            [
                'input' => '150 0',
                'result' => false,
            ],
            [
                'input' => '15 00',
                'result' => false,
            ],
        ];
    }

    /** @dataProvider inputsDataProvider */
    public function testPasses(string $input, bool $result): void
    {
        $passes = app(Money::class)->passes('money', $input);

        $this->assertEquals($result, $passes);
    }
}
