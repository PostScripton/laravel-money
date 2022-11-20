<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class ConvertCurrenciesTest extends TestCase
{
    use InteractsWithConfig;

    /** @test */
    public function moneyCanBeOfflineConvertedBetweenTwoCurrenciesWithoutFailsInNumber(): void
    {
        $rate = 75.32;
        $rub = money('10000000', currency('RUB'));
        $this->assertEquals('1 000 ₽', $rub->toString());

        $usd = $rub->convertInto(currency('USD'), 1 / $rate);
        $this->assertEquals('$ 13.3', $usd->toString());

        $backRub = $usd->convertInto(currency('RUB'), $rate / 1);
        $this->assertEquals('1 000 ₽', $backRub->toString());

        $this->assertTrue($rub->equals($backRub));
        $this->assertTrue($rub->isSameCurrency($backRub));
        $this->assertEquals($rub->getAmount(), $backRub->getAmount());
    }
}
