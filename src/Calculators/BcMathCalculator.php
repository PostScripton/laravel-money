<?php

namespace PostScripton\Money\Calculators;

use InvalidArgumentException;

use function bcadd;
use function bccomp;
use function bcdiv;
use function bcmul;
use function bcsub;

class BcMathCalculator implements Calculator
{
    private const SCALE = 18;

    public function compare(string $a, string $b): int
    {
        return bccomp($a, $b, static::SCALE);
    }

    public function add(string $amount, string $addend): string
    {
        return $this->trimZeros(bcadd($amount, $addend, static::SCALE));
    }

    public function subtract(string $amount, string $subtrahend): string
    {
        return $this->trimZeros(bcsub($amount, $subtrahend, static::SCALE));
    }

    public function multiply(string $amount, string $multiplier): string
    {
        return $this->trimZeros(bcmul($amount, $multiplier, static::SCALE));
    }

    public function divide(string $amount, string $divisor): string
    {
        if ($this->isZero($divisor)) {
            throw new InvalidArgumentException('Division by zero');
        }

        return $this->trimZeros(bcdiv($amount, $divisor, static::SCALE));
    }

    public function ceil(string $amount): string
    {
        if ($this->isZero($amount)) {
            return '0';
        }

        if ($this->isInteger($amount)) {
            return $amount;
        }

        if ($this->isNegative($amount)) {
            return $this->trimZeros(bcadd($amount, '0', 0));
        }

        return $this->trimZeros(bcadd($amount, '1', 0));
    }

    public function floor(string $amount): string
    {
        if ($this->isZero($amount)) {
            return '0';
        }

        if ($this->isInteger($amount)) {
            return $amount;
        }

        if ($this->isNegative($amount)) {
            return $this->trimZeros(bcadd($amount, '-1', 0));
        }

        return $this->trimZeros(bcadd($amount, '0', 0));
    }

    public function absolute(string $amount): string
    {
        return ltrim($amount, '-');
    }

    public function negate(string $amount): string
    {
        if ($this->isZero($amount)) {
            return '0';
        }

        if (str_starts_with(haystack: $amount, needle: '-')) {
            return $this->absolute($amount);
        }

        return '-' . $amount;
    }

    private function isZero(string $amount): bool
    {
        return static::compare($amount, '0') === 0;
    }

    private function isInteger(string $amount): bool
    {
        return ! str_contains($amount, '.');
    }

    private function isNegative(string $amount): bool
    {
        return static::compare($amount, '0') < 0;
    }

    private function trimZeros(string $amount): string
    {
        if (! str_contains($amount, '.')) {
            return $amount;
        }

        return rtrim(rtrim($amount, '0'), '.');
    }
}
