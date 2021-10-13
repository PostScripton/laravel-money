<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;
use PostScripton\Money\Tests\TestCase;

class UploadTest extends TestCase
{
    private $backup_config;

    /** @test */
    public function uploadIntWhenOriginInt()
    {
        Config::set('money.origin', MoneySettings::ORIGIN_INT);

        // don't write this in real code, default origin will be set up automatically from the config
        Money::set(settings()->setOrigin(MoneySettings::ORIGIN_INT));

        $money = money(98765.43210);

        $this->assertEquals(98765, $money->upload());
        $this->assertEquals(money($money->upload())->toString(), $money->toString());
    }

    /** @test */
    public function uploadFloatWhenOriginInt()
    {
        Config::set('money.origin', MoneySettings::ORIGIN_INT);

        // don't write this in real code, default origin will be set up automatically from the config
        Money::set(settings()->setOrigin(MoneySettings::ORIGIN_INT));

        $money = money(98765.43210, settings()->setOrigin(MoneySettings::ORIGIN_FLOAT));

        $this->assertEquals(987654, $money->upload());
        $this->assertEquals(money($money->upload())->toString(), $money->toString());
    }

    /** @test */
    public function uploadFloatWhenOriginFloat()
    {
        Config::set('money.origin', MoneySettings::ORIGIN_FLOAT);

        // don't write this in real code, default origin will be set up automatically from the config
        Money::set(settings()->setOrigin(MoneySettings::ORIGIN_FLOAT));

        $money = money(98765.43210);

        $this->assertEquals(98765.4, $money->upload());
        $this->assertEquals(money($money->upload())->toString(), $money->toString());
    }

    /** @test */
    public function uploadIntWhenOriginFloat()
    {
        Config::set('money.origin', MoneySettings::ORIGIN_FLOAT);

        // don't write this in real code, default origin will be set up automatically from the config
        Money::set(settings()->setOrigin(MoneySettings::ORIGIN_FLOAT));

        $money = money(98765.43210, settings()->setOrigin(MoneySettings::ORIGIN_INT));

        $this->assertEquals(9876.5, $money->upload());
        $this->assertEquals(money($money->upload())->toString(), $money->toString());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->backup_config = Config::get('money');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Config::set('money', $this->backup_config);
    }
}
