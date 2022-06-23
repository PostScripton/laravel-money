# ⏰ Quick start

## Migrations

Decide in which origin you want to store your money whether it is integer or float.
Integer is preferred because of database performance, precision and so on. The database makes more effort to work with DECIMAL, FLOAT, and DOUBLE types, bear in mind they may lose precision as well.

> Using floating point numbers to represent monetary amounts is almost a crime © Robert Martin

More about origins you can read [here](/docs/04_money/object/upload.md).

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    
    // one of them:
    $table->integer('price')->default(0); // for integer origin
    $table->decimal('price')->default(0); // for float origin
    
    $table->timestamps();
});
```

## Models

Cast your model's field to Money type within your Laravel application.

```php
// app/Models/Product.php

use Illuminate\Database\Eloquent\Model;
use PostScripton\Money\Casts\MoneyCast;

class Product extends Model
{
    // ...
    
    protected $casts = [
        // other casts
        
        'price' => MoneyCast::class,
    ];
}
```

👀 See [here](/docs/01_usage/casting.md) for full details.

## How to create and output?

```php
$money = money(1000); // $ 100

$newMoney = $money->clone()                 // clone it to work with independent object
    ->add(500, MoneySettings::ORIGIN_INT)   // $ 150
    ->subtract(money(600))                  // $ 90
    ->divide(1.5);                          // $ 60
$diff = $money->difference($newMoney);      // new Money instance, $40 ($100 - $60)

$money->toString();             // "$ 100"
"Your balance is {$newMoney}";  // "Your balance is $ 60"
"The difference is " . $diff;   // "The difference is $ 40"
```

👀 See [here](/docs/04_money/README.md) for full details.

## Converting currencies

```php
$usd = money(1000); // $ 100

$date = Carbon::parse('2000-12-31');

$offline = $usd->convertInto(currency('RUB'), 75.79);                   // 7 579 ₽
$online = $usd->convertInto(currency('RUB'));                           // 7 139.5 ₽ (today is 2021-10-14)
$onlineHistorical = $usd->convertInto(currency('RUB'), null, $date);    // ~2 816 ₽
```

👀 See [here](/docs/05_services/README.md) for full details.
