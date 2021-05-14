<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Money;

class ConvertCurrenciesTest extends TestCase
{
    /** @test */
    public function money_can_be_offline_converted_between_two_currencies_without_fails_in_number()
    {
        $coeff = 75.32;
        $rub = Money::make(10000, Currency::code('RUB'));
        $this->assertEquals('1 000 ₽', $rub->toString());

        $usd = $rub->convertOfflineInto(Currency::code('USD'), 1 / $coeff);
        $rub = $usd->convertOfflineInto(Currency::code('RUB'), $coeff / 1);

        $this->assertEquals('$ 13.3', $usd->toString());
        $this->assertEquals('1 000 ₽', $rub->toString());
    }
}