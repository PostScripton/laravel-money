<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Tests\TestCase;

class ConvertCurrenciesTest extends TestCase
{
    private $backupConfig;

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
        $this->assertEquals($rub->getPureAmount(), $backRub->getPureAmount());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->backupConfig = Config::get('money');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backupConfig);
    }
}
