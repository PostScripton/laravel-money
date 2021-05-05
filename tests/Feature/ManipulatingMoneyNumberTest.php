<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrencies;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Exceptions\UndefinedOriginException;
use PostScripton\Money\Exceptions\NotNumericException;

class ManipulatingMoneyNumberTest extends TestCase
{
    /** @test
     * @throws UndefinedOriginException
     */
    public function IntAddInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1000, $settings);

        $this->assertEquals('$ 150', $money->add(500));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function IntAddFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1000, $settings);

        $this->assertEquals('$ 150', $money->add(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function FloatAddFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(100, $settings);

        $this->assertEquals('$ 150', $money->add(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function FloatAddInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(100, $settings);

        $this->assertEquals('$ 150', $money->add(500));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function AddNumericError()
    {
        $this->expectException(NotNumericException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->add('asdf');
    }

    /** @test */
    public function AddOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->add(500, 1234);
    }

    /** @test */
    public function AddMoneyHasDifferentCurrenciesError()
    {
        $this->expectException(MoneyHasDifferentCurrencies::class);

        $usd = new Money(1000);
        $rub = new Money(500, Currency::code('RUB'));

        $usd->add($rub);
    }


    /** @test
     * @throws UndefinedOriginException
     */
    public function IntSubtractInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1500, $settings);

        $this->assertEquals('$ 100', $money->subtract(500));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function IntSubtractFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1500, $settings);

        $this->assertEquals('$ 100', $money->subtract(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function FloatSubtractFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(150, $settings);

        $this->assertEquals('$ 100', $money->subtract(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function FloatSubtractInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(150, $settings);

        $this->assertEquals('$ 100', $money->subtract(500));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function SubtractNumericError()
    {
        $this->expectException(NotNumericException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->subtract('asdf');
    }

    /** @test */
    public function SubtractOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->subtract(500, 1234);
    }

    /** @test */
    public function SubtractMoneyHasDifferentCurrenciesError()
    {
        $this->expectException(MoneyHasDifferentCurrencies::class);

        $usd = new Money(1000);
        $rub = new Money(500, Currency::code('RUB'));

        $usd->subtract($rub);
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function IntSubtractInt_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(500, $settings);

        $this->assertEquals('$ -50', $money->subtract(1000)->toString());
        $this->assertEquals(-500, $money->getPureNumber());
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function IntSubtractFloat_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(500, $settings);

        $this->assertEquals('$ -50', $money->subtract(100, MoneySettings::ORIGIN_FLOAT));
        $this->assertEquals(-500, $money->getPureNumber());
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function FloatSubtractFloat_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(50, $settings);

        $this->assertEquals('$ -50', $money->subtract(100, MoneySettings::ORIGIN_FLOAT));
        $this->assertEquals(-50, $money->getPureNumber());
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function FloatSubtractInt_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(50, $settings);

        $this->assertEquals('$ -50', $money->subtract(1000));
        $this->assertEquals(-50, $money->getPureNumber());
    }

    /** @test */
    public function RebaseInt()
    {
        $money = new Money(1500);

        $this->assertEquals('$ 100', $money->rebase(1000));
    }

    /** @test */
    public function RebaseFloat()
    {
        $money = new Money(1500);

        $this->assertEquals('$ 100', $money->rebase(100, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test
     * @throws UndefinedOriginException
     */
    public function RebaseNumericError()
    {
        $this->expectException(NotNumericException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->rebase('asdf');
    }

    /** @test */
    public function RebaseOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->rebase(500, 1234);
    }

    /** @test */
    public function RebaseMoneyHasDifferentCurrenciesError()
    {
        $this->expectException(MoneyHasDifferentCurrencies::class);

        $usd = new Money(1000);
        $rub = new Money(500, Currency::code('RUB'));

        $usd->rebase($rub);
    }
}