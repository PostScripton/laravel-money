<?php

namespace PostScripton\Money\Tests\Unit;

use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class ConvertCurrenciesTest extends TestCase
{
    use InteractsWithConfig;

    public function testOfflineConvertingMayLooseAccuracy(): void
    {
        $rate = 75.32;
        $rub = money('10000000', currency('RUB'));
        $this->assertEquals('1 000 ₽', $rub->toString());

        $usd = $rub->convertInto(currency('USD'), 1 / $rate);
        $this->assertEquals('$ 13.3', $usd->toString());

        $backRub = $usd->convertInto(currency('RUB'), $rate / 1);
        $this->assertEquals('1 000 ₽', $backRub->toString());

        $this->assertFalse($rub->equals($backRub));
        $this->assertTrue($rub->isSameCurrency($backRub));
        $this->assertEquals('9999935', $backRub->getAmount());
    }
}
