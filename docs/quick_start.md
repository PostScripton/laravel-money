# ⏰ Quick start

## Migrations

The integer type is **the only choice** because of database performance, precision and so on. The database makes more effort to work with DECIMAL, FLOAT, and DOUBLE types, bear in mind they may lose precision as well.

> Using floating point numbers to represent monetary amounts is almost a crime © Robert Martin

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->money('price')->default(0);
    $table->timestamps();
});
```

Big Integer is preferred because its size is enormous: 8 bytes which is 2^64-1 in MySQL and PostgreSQL.
If you know that you'll be storing a giant **positive** numbers, you can take a look at Unsigned Bit Integer. If you need to store negative ones as well, you can specify an additional column to represent a type `debit` (positive) / `credit` (negative).

```php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->unsignedMoney('amount');
    $table->enum('transaction_type', ['debit', 'credit']);
    $table->enum('type', ['invoice', 'fee', 'order', 'admin']);
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
$money = money('1000000');      // $ 100
$money = money_parse('100');    // $ 100

$newMoney = $money->clone()             // clone it to work with independent object
    ->add(money('500000'))              // $ 150
    ->subtract(money('600000'))         // $ 90
    ->divide(1.5);                      // $ 60
$diff = $money->difference($newMoney);  // new Money instance, $40 ($100 - $60)

$money->toString();             // "$ 100"
"Your balance is {$newMoney}";  // "Your balance is $ 60"
"The difference is " . $diff;   // "The difference is $ 40"
```

👀 See [here](/docs/04_money/README.md) for full details.

## Converting currencies

```php
$usd = money('1000000'); // $ 100

$date = Carbon::parse('2000-12-31');

$offline = $usd->offlineConvertTo('RUB', 75.79);    // 7 579 ₽
$online = $usd->convertTo(currency('RUB'));         // 7 139.5 ₽ (today is 2021-10-14)
$onlineHistorical = $usd->convertTo('RUB', $date);  // ~2 816 ₽
```

👀 See [here](/docs/05_rate_exchangers/README.md) for full details.
