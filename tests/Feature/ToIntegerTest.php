<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Money;

class ToIntegerTest extends TestCase
{
    /** @test
     * @throws CurrencyDoesNotExistException
     */
    public function StaticToInteger()
    {
        $db_int = 12345;
        $money = Money::make($db_int, Currency::code('RUB'));

        $this->assertEquals($db_int, Money::integer($money));
    }

    /** @test
     * @throws CurrencyDoesNotExistException
     */
    public function StaticToIntegerAfterConverting()
    {
        $coeff = 75.32;
        $rub = Money::make(10000, Currency::code('RUB'));

        $usd = Money::convertOffline($rub, Currency::code('USD'), 1 / $coeff);

        $this->assertEquals(132, Money::integer($usd));
    }

    /** @test
     * @throws CurrencyDoesNotExistException
     */
    public function ToInteger()
    {
        $db_int = 12345;
        $money = Money::make($db_int, Currency::code('RUB'));

        $this->assertEquals($db_int, $money->toInteger());
    }

    /** @test
     * @throws CurrencyDoesNotExistException
     */
    public function ToIntegerAfterConverting()
    {
        $coeff = 75.32;
        $rub = Money::make(10000, Currency::code('RUB'));

        $usd = Money::convertOffline($rub, Currency::code('USD'), 1 / $coeff);

        $this->assertEquals(132, $usd->toInteger());
    }
}