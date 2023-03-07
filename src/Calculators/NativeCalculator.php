<?php

namespace PostScripton\Money\Calculators;

use InvalidArgumentException;

class NativeCalculator implements Calculator
{
    public function compare(string $a, string $b): int
    {
        return (float) $a <=> (float) $b;
    }

    public function add(string $amount, string $addend): string
    {
        $result = (float) $amount + (float) $addend;

        return $this->trimZeros($this->format($result));
    }

    public function subtract(string $amount, string $subtrahend): string
    {
        $result = (float) $amount - (float) $subtrahend;

        return $this->trimZeros($this->format($result));
    }

    public function multiply(string $amount, string $multiplier): string
    {
        $result = (float) $amount * (float) $multiplier;

        return $this->trimZeros($this->format($result));
    }

    public function divide(string $amount, string $divisor): string
    {
        if ($this->isZero($divisor)) {
            throw new InvalidArgumentException('Division by zero');
        }

        $result = (float) $amount / (float) $divisor;

        return $this->trimZeros($this->format($result));
    }

    public function ceil(string $amount): string
    {
        $result = ceil((float) $amount);

        return $this->trimZeros($this->format($result));
    }

    public function floor(string $amount): string
    {
        $result = floor((float) $amount);

        return $this->trimZeros($this->format($result));
    }

    public function absolute(string $amount): string
    {
        $result = abs((float) $amount);

        return $this->trimZeros($this->format($result));
    }

    public function negate(string $amount): string
    {
        $result = ((float) $amount) * -1;

        return $this->trimZeros($this->format($result));
    }

    private function isZero(string $amount): bool
    {
        return static::compare($amount, '0') === 0;
    }

    private function format(float $amount): string
    {
        $format = '%.9f';

        return sprintf($format, $amount);
    }

    private function trimZeros(string $amount): string
    {
        return rtrim(rtrim($amount, '0'), '.');
    }
}
