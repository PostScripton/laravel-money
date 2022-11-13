<?php

namespace PostScripton\Money\Tests;

use Illuminate\Support\Facades\Config;

trait InteractsWithConfig
{
    private mixed $backupConfig;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpConfig();
    }

    protected function tearDown(): void
    {
        $this->tearDownConfig();
        parent::tearDown();
    }

    protected function setUpConfig(): void
    {
        $this->backupConfig = Config::get($this->configName);
    }

    protected function tearDownConfig(): void
    {
        Config::set([$this->configName => $this->backupConfig]);
    }
}
