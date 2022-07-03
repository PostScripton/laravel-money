<?php

namespace PostScripton\Money\Tests\Feature;

use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\MoneyHasDifferentCurrenciesException;
use PostScripton\Money\Exceptions\NotNumericOrMoneyException;
use PostScripton\Money\Exceptions\UndefinedOriginException;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Tests\TestCase;

class ManipulatingMoneyNumberTest extends TestCase
{
    /** @test */
    public function intAddInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1000, $settings);

        $this->assertEquals('$ 150', $money->add(500));
    }

    /** @test */
    public function intAddFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1000, $settings);

        $this->assertEquals('$ 150', $money->add(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function floatAddFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(100, $settings);

        $this->assertEquals('$ 150', $money->add(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function floatAddInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(100, $settings);

        $this->assertEquals('$ 150', $money->add(500));
    }

    /** @test */
    public function addNumericError()
    {
        $this->expectException(NotNumericOrMoneyException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->add('asdf');
    }

    /** @test */
    public function addOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->add(500, 1234);
    }

    /** @test */
    public function addMoneyHasDifferentCurrenciesError()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $usd = new Money(1000);
        $rub = new Money(500, Currency::code('RUB'));

        $usd->add($rub);
    }


    /** @test */
    public function intSubtractInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1500, $settings);

        $this->assertEquals('$ 100', $money->subtract(500));
    }

    /** @test */
    public function intSubtractFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(1500, $settings);

        $this->assertEquals('$ 100', $money->subtract(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function floatSubtractFloat()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(150, $settings);

        $this->assertEquals('$ 100', $money->subtract(50, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function floatSubtractInt()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(150, $settings);

        $this->assertEquals('$ 100', $money->subtract(500));
    }

    /** @test */
    public function subtractNumericError()
    {
        $this->expectException(NotNumericOrMoneyException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->subtract('asdf');
    }

    /** @test */
    public function subtractOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->subtract(500, 1234);
    }

    /** @test */
    public function subtractMoneyHasDifferentCurrenciesError()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $usd = new Money(1000);
        $rub = new Money(500, Currency::code('RUB'));

        $usd->subtract($rub);
    }

    /** @test */
    public function intSubtractIntMoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(500, $settings);

        $this->assertEquals('$ -50', $money->subtract(1000)->toString());
        $this->assertEquals(-500, $money->getPureAmount());
    }

    /** @test */
    public function intSubtractFloatMoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(500, $settings);

        $this->assertEquals('$ -50', $money->subtract(100, MoneySettings::ORIGIN_FLOAT));
        $this->assertEquals(-500, $money->getPureAmount());
    }

    /** @test */
    public function floatSubtractFloatMoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(50, $settings);

        $this->assertEquals('$ -50', $money->subtract(100, MoneySettings::ORIGIN_FLOAT));
        $this->assertEquals(-50, $money->getPureAmount());
    }

    /** @test */
    public function floatSubtractIntMoreThanMoneyHas()
    {
        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_FLOAT);
        $money = new Money(50, $settings);

        $this->assertEquals('$ -50', $money->subtract(1000));
        $this->assertEquals(-50, $money->getPureAmount());
    }

    /** @test */
    public function rebaseInt()
    {
        $money = new Money(1500);

        $this->assertEquals('$ 100', $money->rebase(1000));
    }

    /** @test */
    public function rebaseFloat()
    {
        $money = new Money(1500);

        $this->assertEquals('$ 100', $money->rebase(100, MoneySettings::ORIGIN_FLOAT));
    }

    /** @test */
    public function rebaseNumericError()
    {
        $this->expectException(NotNumericOrMoneyException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->rebase('asdf');
    }

    /** @test */
    public function rebaseOriginError()
    {
        $this->expectException(UndefinedOriginException::class);

        $settings = (new MoneySettings())
            ->setOrigin(MoneySettings::ORIGIN_INT);
        $money = new Money(100, $settings);

        $money->rebase(500, 1234);
    }

    /** @test */
    public function rebaseMoneyHasDifferentCurrenciesError()
    {
        $this->expectException(MoneyHasDifferentCurrenciesException::class);

        $usd = new Money(1000);
        $rub = new Money(500, Currency::code('RUB'));

        $usd->rebase($rub);
    }

    /** @test */
    public function moneyCanBeMultipliedByANumber()
    {
        $money = new Money(500);
        $money->multiply(1.5);

        $this->assertEquals(750, $money->getPureAmount());
        $this->assertEquals('$ 75', $money->toString());
    }

    /** @test */
    public function moneyCanBeDividedByANumber()
    {
        $money = new Money(1000);
        $money->divide(2);

        $this->assertEquals(500, $money->getPureAmount());
        $this->assertEquals('$ 50', $money->toString());
    }

    protected function setUp(): void
    {
        parent::setUp();
        Currency::setCurrencyList(Currency::currentList());
    }
}
