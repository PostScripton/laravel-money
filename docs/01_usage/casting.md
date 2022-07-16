# 🎯 Casting

You can cast your model's field to Money type and next work with the money object within your Laravel application.

There's an example:

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

Now the price attribute will always be a type of Money.
Here's how you can use it:

```php
$product = new Product();
// ...

$product->price; // Money instance, for example, $ 120 is set somewhere above
$product->price->divide(2); // sale 50% => $ 60

$commission = money('100000'); // $ 10
$checkout = $product->price->clone()->add($commission); // Money instance, $ 70
```
> Note that you can't just add the commission because it will affect the price field as well, but we want another money object to work with it independently.
> More about cloning you can read [here](/docs/01_usage/cloning.md).

More about casting in Laravel you can read in [the official documentation](https://laravel.com/docs/8.x/eloquent-mutators#attribute-casting).

---

📌 Back to the [contents](/README.md#table-of-contents).
