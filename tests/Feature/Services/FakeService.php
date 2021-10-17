<?php

namespace PostScripton\Money\Tests\Feature\Services;

use Mockery\MockInterface;
use PostScripton\Money\Services\ServiceInterface;

trait FakeService
{
    protected function mockService()
    {
        $this->mock(ServiceInterface::class, function (MockInterface $mock) {
            return $mock
                ->shouldReceive('getClassName')
                ->andReturn('SomeServiceClass')
                ->shouldReceive('supports')
                ->withAnyArgs()
                ->andReturnUsing(function ($isos) {
                    return array_diff($isos, ['RUB', 'USD']);
                })
                ->shouldReceive('rate')
                ->withAnyArgs()
                ->andReturnUsing(function ($from, $to, $date = null) {
                    if ($from === $to) {
                        return 1;
                    }

                    switch ($to) {
                        case 'RUB':
                            return $date ? 28.16 : 75.32;
                        case 'USD':
                            return $date
                                ? 0.03551136363
                                : 0.01327668613914;
                        default:
                            return 1;
                    }
                });
        });
    }
}
