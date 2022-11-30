<?php

namespace PostScripton\Money\Tests\Unit\Calculators;

use InvalidArgumentException;
use PostScripton\Money\Calculators\BcMathCalculator;
use PostScripton\Money\Tests\TestCase;

class BcMathCalculatorTest extends TestCase
{
    /** @dataProvider compareDataProvider */
    public function testCompare(string $a, string $b, int $expected): void
    {
        $result = app(BcMathCalculator::class)->compare($a, $b);

        $this->assertEquals($expected, $result);
    }

    /** @dataProvider addDataProvider */
    public function testAdd(string $amount, string $addend, string $expected): void
    {
        $result = app(BcMathCalculator::class)->add($amount, $addend);

        $this->assertEquals($expected, $result);
    }

    /** @dataProvider subtractDataProvider */
    public function testSubtract(string $amount, string $subtrahend, string $expected): void
    {
        $result = app(BcMathCalculator::class)->subtract($amount, $subtrahend);

        $this->assertEquals($expected, $result);
    }

    /** @dataProvider multiplyDataProvider */
    public function testMultiply(string $amount, string|float $multiplier, string $expected): void
    {
        $result = app(BcMathCalculator::class)->multiply($amount, $multiplier);

        $this->assertEquals($expected, $result);
    }

    /** @dataProvider divideDataProvider */
    public function testDivide(string $amount, string|float $divisor, string $expected): void
    {
        $result = app(BcMathCalculator::class)->divide($amount, $divisor);

        $this->assertEquals($expected, $result);
    }

    public function testDivideByZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero');

        app(BcMathCalculator::class)->divide('100', '0');
    }

    /** @dataProvider ceilDataProvider */
    public function testCeil(string $amount, string $expected): void
    {
        $result = app(BcMathCalculator::class)->ceil($amount);

        $this->assertEquals($expected, $result);
    }

    /** @dataProvider floorDataProvider */
    public function testFloor(string $amount, string $expected): void
    {
        $result = app(BcMathCalculator::class)->floor($amount);

        $this->assertEquals($expected, $result);
    }

    /** @dataProvider absoluteDataProvider */
    public function testAbsolute(string $amount, string $expected): void
    {
        $result = app(BcMathCalculator::class)->absolute($amount);

        $this->assertEquals($expected, $result);
    }

    protected function compareDataProvider(): array
    {
        return [
            ['a' => '10', 'b' => '5', 'expected' => 1],
            ['a' => '5', 'b' => '10', 'expected' => -1],
            ['a' => '10', 'b' => '10', 'expected' => 0],
            ['a' => '10.0001', 'b' => '10', 'expected' => 1],
            ['a' => '10', 'b' => '10.0001', 'expected' => -1],
            ['a' => '0', 'b' => '0', 'expected' => 0],
        ];
    }

    protected function addDataProvider(): array
    {
        return [
            ['amount' => '100', 'addend' => '50', 'expected' => '150'],
            ['amount' => '100', 'addend' => '-50', 'expected' => '50'],
            ['amount' => '-100', 'addend' => '50', 'expected' => '-50'],
            ['amount' => '-100', 'addend' => '-50', 'expected' => '-150'],
            ['amount' => '100', 'addend' => '0', 'expected' => '100'],
            ['amount' => '100', 'addend' => '50.0001', 'expected' => '150.0001'],
            ['amount' => '100', 'addend' => '-0.0001', 'expected' => '99.9999'],
        ];
    }

    protected function subtractDataProvider(): array
    {
        return [
            ['amount' => '100', 'subtrahend' => '50', 'expected' => '50'],
            ['amount' => '100', 'subtrahend' => '-50', 'expected' => '150'],
            ['amount' => '-100', 'subtrahend' => '50', 'expected' => '-150'],
            ['amount' => '-100', 'subtrahend' => '-50', 'expected' => '-50'],
            ['amount' => '100', 'subtrahend' => '0', 'expected' => '100'],
            ['amount' => '100', 'subtrahend' => '50.0001', 'expected' => '49.9999'],
            ['amount' => '100', 'subtrahend' => '-0.0001', 'expected' => '100.0001'],
        ];
    }

    protected function multiplyDataProvider(): array
    {
        return [
            ['amount' => '100', 'multiplier' => '2', 'expected' => '200'],
            ['amount' => '100', 'multiplier' => '1.5', 'expected' => '150'],
            ['amount' => '100', 'multiplier' => '1', 'expected' => '100'],
            ['amount' => '100', 'multiplier' => '0.5', 'expected' => '50'],
            ['amount' => '100', 'multiplier' => '0.1', 'expected' => '10'],
            ['amount' => '100', 'multiplier' => '-2', 'expected' => '-200'],
            ['amount' => '100', 'multiplier' => '-1.5', 'expected' => '-150'],
            ['amount' => '100', 'multiplier' => '-1', 'expected' => '-100'],
            ['amount' => '100', 'multiplier' => '-0.5', 'expected' => '-50'],
            ['amount' => '100', 'multiplier' => '-0.1', 'expected' => '-10'],
            ['amount' => '100', 'multiplier' => '0', 'expected' => '0'],
            ['amount' => '100', 'multiplier' => 2, 'expected' => '200'],
            ['amount' => '100', 'multiplier' => 1.5, 'expected' => '150'],
            ['amount' => '100', 'multiplier' => 1, 'expected' => '100'],
            ['amount' => '100', 'multiplier' => 0.5, 'expected' => '50'],
            ['amount' => '100', 'multiplier' => 0.1, 'expected' => '10'],
            ['amount' => '100', 'multiplier' => 0, 'expected' => '0'],
        ];
    }

    protected function divideDataProvider(): array
    {
        return [
            ['amount' => '100', 'divisor' => '2', 'expected' => '50'],
            ['amount' => '100', 'divisor' => '1.5', 'expected' => '66.666666666666666666'],
            ['amount' => '100', 'divisor' => '1', 'expected' => '100'],
            ['amount' => '100', 'divisor' => '0.5', 'expected' => '200'],
            ['amount' => '100', 'divisor' => '0.1', 'expected' => '1000'],
            ['amount' => '100', 'divisor' => '-2', 'expected' => '-50'],
            ['amount' => '100', 'divisor' => '-1.5', 'expected' => '-66.666666666666666666'],
            ['amount' => '100', 'divisor' => '-1', 'expected' => '-100'],
            ['amount' => '100', 'divisor' => '-0.5', 'expected' => '-200'],
            ['amount' => '100', 'divisor' => '-0.1', 'expected' => '-1000'],
            ['amount' => '100', 'divisor' => 2, 'expected' => '50'],
            ['amount' => '100', 'divisor' => 1.5, 'expected' => '66.666666666666666666'],
            ['amount' => '100', 'divisor' => 1, 'expected' => '100'],
            ['amount' => '100', 'divisor' => 0.5, 'expected' => '200'],
            ['amount' => '100', 'divisor' => 0.1, 'expected' => '1000'],
        ];
    }

    protected function ceilDataProvider(): array
    {
        return [
            ['amount' => '0', 'expected' => '0'],
            ['amount' => '0.25', 'expected' => '1'],
            ['amount' => '100', 'expected' => '100'],
            ['amount' => '100.25', 'expected' => '101'],
            ['amount' => '100.0001', 'expected' => '101'],
            ['amount' => '-0', 'expected' => '-0'],
            ['amount' => '-0.25', 'expected' => '0'],
            ['amount' => '-100', 'expected' => '-100'],
            ['amount' => '-100.25', 'expected' => '-100'],
            ['amount' => '-100.0001', 'expected' => '-100'],
        ];
    }

    protected function floorDataProvider(): array
    {
        return [
            ['amount' => '0', 'expected' => '0'],
            ['amount' => '0.25', 'expected' => '0'],
            ['amount' => '100', 'expected' => '100'],
            ['amount' => '100.25', 'expected' => '100'],
            ['amount' => '100.0001', 'expected' => '100'],
            ['amount' => '-0', 'expected' => '-0'],
            ['amount' => '-0.25', 'expected' => '-1'],
            ['amount' => '-100', 'expected' => '-100'],
            ['amount' => '-100.25', 'expected' => '-101'],
            ['amount' => '-100.0001', 'expected' => '-101'],
        ];
    }

    protected function absoluteDataProvider(): array
    {
        return [
            ['amount' => '0', 'expected' => '0'],
            ['amount' => '0.25', 'expected' => '0.25'],
            ['amount' => '100', 'expected' => '100'],
            ['amount' => '-0', 'expected' => '0'],
            ['amount' => '-0.25', 'expected' => '0.25'],
            ['amount' => '-100', 'expected' => '100'],
        ];
    }
}
