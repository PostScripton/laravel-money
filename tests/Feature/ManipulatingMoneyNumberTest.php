<?php

namespace PostScripton\Money\Tests;

use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Exceptions\UndefinedOriginException;
use PostScripton\Money\Exceptions\NotNumericException;

class ManipulatingMoneyNumberTest extends TestCase
{
    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
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
     * @throws NotNumericException
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
     * @throws NotNumericException
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
     * @throws NotNumericException
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

    /** @test
     * @throws NotNumericException
     */
    public function AddOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->add(500, 1234);
    }


    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
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
     * @throws NotNumericException
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
     * @throws NotNumericException
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
     * @throws NotNumericException
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

    /** @test
     * @throws NotNumericException
     */
    public function SubtractOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->subtract(500, 1234);
    }

    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
     */
    public function IntSubtractInt_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(500, $settings);

        $this->assertEquals('$ 0', $money->subtract(1000));
        $this->assertEquals(0, $money->getPureNumber());
    }

    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
     */
    public function IntSubtractFloat_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(500, $settings);

        $this->assertEquals('$ 0', $money->subtract(100, MoneySettings::ORIGIN_FLOAT));
        $this->assertEquals(0, $money->getPureNumber());
    }

    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
     */
    public function FloatSubtractFloat_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(50, $settings);

        $this->assertEquals('$ 0', $money->subtract(100, MoneySettings::ORIGIN_FLOAT));
        $this->assertEquals(0, $money->getPureNumber());
    }

    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
     */
    public function FloatSubtractInt_MoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(50, $settings);

        $this->assertEquals('$ 0', $money->subtract(1000));
        $this->assertEquals(0, $money->getPureNumber());
    }

    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
     */
    public function RebaseInt()
    {
        $money = new Money(1500);

        $this->assertEquals('$ 100', $money->rebase(1000));
    }

    /** @test
     * @throws UndefinedOriginException
     * @throws NotNumericException
     */
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

    /** @test
     * @throws NotNumericException
     */
    public function RebaseOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->rebase(500, 1234);
    }
}