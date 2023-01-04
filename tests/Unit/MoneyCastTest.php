<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use PostScripton\Money\Casts\MoneyCast;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyCastTest extends TestCase
{
    public function testCast(): void
    {
        $product1 = $this->getTestingModel();
        $product1->price = money(12345000);
        $product2 = $this->getTestingModel();
        $product2->price = '1 234.5';
        $product3 = $this->getTestingModel();
        $product3->price = money('12345000');
        $product4 = $this->getTestingModel();
        $product4->price = null;
        $expectedMoneyString = '$ 1 234.5';

        $this->assertInstanceOf(Money::class, $product1->price);
        $this->assertEquals($expectedMoneyString, $product1->price->toString());
        $this->assertInstanceOf(Money::class, $product2->price);
        $this->assertEquals($expectedMoneyString, $product2->price->toString());
        $this->assertInstanceOf(Money::class, $product3->price);
        $this->assertEquals($expectedMoneyString, $product3->price->toString());
        $this->assertNull($product4->price);
    }

    private function getTestingModel(): Model
    {
        return new class extends Model {
            protected $fillable = [
                'price',
            ];

            protected $casts = [
                'price' => MoneyCast::class,
            ];
        };
    }
}
