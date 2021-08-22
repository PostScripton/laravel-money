<?php

namespace PostScripton\Money\Tests\Feature;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Tests\TestCase;

class DifferenceBetweenMoneyTest extends TestCase
{
    private $backup_config;

    /** @test */
    public function differenceReturnsAString()
    {
        $m1 = money(500);
        $m2 = money(1000);

        $this->assertIsString($m1->difference($m2));
    }

    /** @test */
    public function differenceWithTwoSameCurrencies()
    {
        $m1 = money(500);
        $m2 = money(1000);

        $this->assertEquals(money(500)->subtract($m2)->toString(), $m1->difference($m2));
    }

    /** @test */
    public function differenceWithTwoDifferentCurrencies()
    {
        $usd = money(500);
        $rub = money(1000, currency('rub'));
        $rub_into_usd = $rub->convertInto($usd->getCurrency());

        $this->assertEquals(money(500)->subtract($rub_into_usd)->toString(), $usd->difference($rub_into_usd));
    }

    /** @test */
    public function anExceptionIsThrownWhenThereAreTwoDifferentCurrencies()
    {
        $m1 = money(500);
        $m2 = money(1000, currency('rub'));

        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $m1->difference($m2);
    }

    /** @test */
    public function newSettingsCanBeAppliedToTheDifference()
    {
        $m1 = money(500);
        $m2 = money(1000);

        $this->assertEquals(
            money(500)->subtract($m2)->toString() . '.0',
            $m1->difference($m2, settings()->setEndsWith0(true))
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->backup_config = Config::get('money');
        Currency::setCurrencyList(Currency::currentList());
        Config::set('money.service', 'exchangerate');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backup_config);
    }
}
