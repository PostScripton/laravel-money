<?php

namespace PostScripton\Money\Tests;

use Illuminate\Support\Facades\Config;

trait InteractsWithConfig
{
    private mixed $backupConfig;

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupConfig = Config::get($this->configName);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Config::set([$this->configName => $this->backupConfig]);
    }
}
