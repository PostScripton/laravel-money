<?php

namespace PostScripton\Money\Tests\Unit\Calculators;

use Closure;
use InvalidArgumentException;
use PHPUnit\Framework\AssertionFailedError;
use PostScripton\Money\Calculators\BcMathCalculator;
use PostScripton\Money\Calculators\Calculator;
use PostScripton\Money\Calculators\NativeCalculator;
use PostScripton\Money\Tests\TestCase;
use Throwable;

class CalculatorTest extends TestCase
{
    private const CALCULATORS = [
        'BcMath' => BcMathCalculator::class,
        'Native' => NativeCalculator::class,
    ];

    /** @dataProvider providerCompare */
    public function testCompare(string $a, string $b, int $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->compare($a, $b),
            expected: $expected,
        );
    }

    public function providerCompare(): array
    {
        return [
            ['a' => '10', 'b' => '5', 'expected' => 1],
            ['a' => '5', 'b' => '10', 'expected' => -1],
            ['a' => '10', 'b' => '10', 'expected' => 0],
            ['a' => '10.0001', 'b' => '10', 'expected' => 1],
            ['a' => '10', 'b' => '10.0001', 'expected' => -1],
            ['a' => '0', 'b' => '0', 'expected' => 0],
            ['a' => '-0', 'b' => '0', 'expected' => 0],
            ['a' => '0', 'b' => '-0', 'expected' => 0],
            ['a' => '-0', 'b' => '-0', 'expected' => 0],
        ];
    }

    /** @dataProvider providerAdd */
    public function testAdd(string $amount, string $addend, string $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->add($amount, $addend),
            expected: $expected,
        );
    }

    public function providerAdd(): array
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

    /** @dataProvider providerSubtract */
    public function testSubtract(string $amount, string $subtrahend, string $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->subtract($amount, $subtrahend),
            expected: $expected,
        );
    }

    public function providerSubtract(): array
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

    /** @dataProvider providerMultiply */
    public function testMultiply(string $amount, string|float $multiplier, string $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->multiply($amount, $multiplier),
            expected: $expected,
        );
    }

    public function providerMultiply(): array
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

    /** @dataProvider providerDivide */
    public function testDivide(string $amount, string|float $divisor, string|array $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->divide($amount, $divisor),
            expected: $expected,
        );
    }

    public function providerDivide(): array
    {
        return [
            ['amount' => '100', 'divisor' => '2', 'expected' => '50'],
            ['amount' => '100', 'divisor' => '1.5', 'expected' => ['66.666666666666666666', '66.666666667']],
            ['amount' => '100', 'divisor' => '1', 'expected' => '100'],
            ['amount' => '100', 'divisor' => '0.5', 'expected' => '200'],
            ['amount' => '100', 'divisor' => '0.1', 'expected' => '1000'],
            ['amount' => '100', 'divisor' => '-2', 'expected' => '-50'],
            ['amount' => '100', 'divisor' => '-1.5', 'expected' => ['-66.666666666666666666', '-66.666666667']],
            ['amount' => '100', 'divisor' => '-1', 'expected' => '-100'],
            ['amount' => '100', 'divisor' => '-0.5', 'expected' => '-200'],
            ['amount' => '100', 'divisor' => '-0.1', 'expected' => '-1000'],
            ['amount' => '100', 'divisor' => 2, 'expected' => '50'],
            ['amount' => '100', 'divisor' => 1.5, 'expected' => ['66.666666666666666666', '66.666666667']],
            ['amount' => '100', 'divisor' => 1, 'expected' => '100'],
            ['amount' => '100', 'divisor' => 0.5, 'expected' => '200'],
            ['amount' => '100', 'divisor' => 0.1, 'expected' => '1000'],
            ['amount' => '100', 'divisor' => '0', 'expected' => [InvalidArgumentException::class, 'Division by zero']],
        ];
    }

    /** @dataProvider providerCeil */
    public function testCeil(string $amount, string $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->ceil($amount),
            expected: $expected,
        );
    }

    public function providerCeil(): array
    {
        return [
            ['amount' => '0', 'expected' => '0'],
            ['amount' => '0.25', 'expected' => '1'],
            ['amount' => '100', 'expected' => '100'],
            ['amount' => '100.25', 'expected' => '101'],
            ['amount' => '100.0001', 'expected' => '101'],
            ['amount' => '-0', 'expected' => '0'],
            ['amount' => '-0.25', 'expected' => '0'],
            ['amount' => '-100', 'expected' => '-100'],
            ['amount' => '-100.25', 'expected' => '-100'],
            ['amount' => '-100.0001', 'expected' => '-100'],
        ];
    }

    /** @dataProvider providerFloor */
    public function testFloor(string $amount, string $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->floor($amount),
            expected: $expected,
        );
    }

    public function providerFloor(): array
    {
        return [
            ['amount' => '0', 'expected' => '0'],
            ['amount' => '0.25', 'expected' => '0'],
            ['amount' => '100', 'expected' => '100'],
            ['amount' => '100.25', 'expected' => '100'],
            ['amount' => '100.0001', 'expected' => '100'],
            ['amount' => '-0', 'expected' => '0'],
            ['amount' => '-0.25', 'expected' => '-1'],
            ['amount' => '-100', 'expected' => '-100'],
            ['amount' => '-100.25', 'expected' => '-101'],
            ['amount' => '-100.0001', 'expected' => '-101'],
        ];
    }

    /** @dataProvider providerAbsolute */
    public function testAbsolute(string $amount, string $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->absolute($amount),
            expected: $expected,
        );
    }

    public function providerAbsolute(): array
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

    /** @dataProvider providerNegate */
    public function testNegate(string $amount, string $expected): void
    {
        $this->runTestsForCalculators(
            callback: fn(Calculator $calculator) => $calculator->negate($amount),
            expected: $expected,
        );
    }

    public function providerNegate(): array
    {
        return [
            ['amount' => '-0', 'expected' => '0'],
            ['amount' => '0', 'expected' => '0'],
            ['amount' => '10', 'expected' => '-10'],
            ['amount' => '-10', 'expected' => '10'],
            ['amount' => '0.25', 'expected' => '-0.25'],
            ['amount' => '-0.25', 'expected' => '0.25'],
        ];
    }

    private function runTestsForCalculators(Closure $callback, mixed $expected): void
    {
        foreach (self::CALCULATORS as $name => $class) {
            $isException = is_array($expected) && $this->isExceptionClass($expected[0] ?? null);

            if ($isException) {
                [$exception, $exceptionMessage] = $expected;

                try {
                    $callback(app($class));

                    $this->fail(sprintf('No excepted exception thrown [%s]: %s', $exception, $exceptionMessage));
                } catch (AssertionFailedError $e) {
                    throw $e;
                } catch (Throwable $e) {
                    $this->assertInstanceOf($exception, $e);
                    $this->assertEquals($exceptionMessage, $e->getMessage());
                    continue;
                }
            }

            $result = $callback(app($class));

            if (is_array($expected)) {
                $this->assertContains($result, $expected, $this->getEqualsErrorMessage($name));
                continue;
            }

            $this->assertEquals($expected, $result, $this->getEqualsErrorMessage($name));
        }
    }

    private function getEqualsErrorMessage(string $calculatorName): string
    {
        return "{$calculatorName} calculator returned unexpected value";
    }
}
