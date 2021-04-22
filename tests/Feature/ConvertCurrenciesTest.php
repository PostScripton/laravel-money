<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\CurrencyDoesNotExistException;
use PostScripton\Money\Money;

class ConvertCurrenciesTest extends TestCase
{
    /** @test
     * @throws CurrencyDoesNotExistException
     */
    public function StaticConvertCurrenciesOffline()
    {
        $coeff = 75.32;
        $rub = Money::make(10000, Currency::code('RUB'));

        $usd = Money::convertOffline($rub, Currency::code('USD'), 1 / $coeff);
        $rub = Money::convertOffline($usd, Currency::code('RUB'), $coeff / 1);

        $this->assertEquals('$ 13.3', $usd);
        $this->assertEquals('1 000 ₽', $rub);
    }

    /** @test
     * @throws CurrencyDoesNotExistException
     */
    public function ConvertCurrenciesOffline()
    {
        $coeff = 75.32;
        $rub = Money::make(10000, Currency::code('RUB'));

        $usd = $rub->convertOfflineInto(Currency::code('USD'), 1 / $coeff);
        $rub = $usd->convertOfflineInto(Currency::code('RUB'), $coeff / 1);

        $this->assertEquals('$ 13.3', $usd);
        $this->assertEquals('1 000 ₽', $rub);
    }
}