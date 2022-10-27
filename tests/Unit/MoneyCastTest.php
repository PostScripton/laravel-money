<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use PostScripton\Money\Casts\MoneyCast;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\TestCase;

class MoneyCastTest extends TestCase
{
    public function testGet(): void
    {
        $product1 = $this->getTestingModel();
        $product2 = $this->getTestingModel();
        $product2->price = money('12345');

        $this->assertNull($product1->price);
        $this->assertInstanceOf(Money::class, $product2->price);
        $this->assertEquals('$ 1 234.5', $product2->price->toString());
    }

    public function testSet(): void
    {
        $product1 = $this->getTestingModel();
        $product1->price = money('12345');
        $product2 = $this->getTestingModel();
        $product2->price = '12345';
        $product3 = $this->getTestingModel();
        $product3->price = 12345;
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
