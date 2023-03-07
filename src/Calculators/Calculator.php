<?php

namespace PostScripton\Money\Calculators;

interface Calculator
{
    public function compare(string $a, string $b): int;

    public function add(string $amount, string $addend): string;

    public function subtract(string $amount, string $subtrahend): string;

    public function multiply(string $amount, string $multiplier): string;

    public function divide(string $amount, string $divisor): string;

    public function ceil(string $amount): string;

    public function floor(string $amount): string;

    public function absolute(string $amount): string;

    public function negate(string $amount): string;
}
