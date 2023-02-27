# `Money::avg()`

calculates the average out of a sequence of monetary objects.

## Exceptions

1. `MoneyHasDifferentCurrenciesException` - is thrown when one of monetary objects has a different currency.

## Usage

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

$m1 = money_parse('300');
$m2 = money_parse('100');
$m3 = money_parse('200');
$collection = collect([$m1, $m2, $m3]);

Money::avg();                           // null
Money::avg(collect());                  // null
Money::avg($m1, $m2, $m3);              // $ 200
Money::avg($collection);                // $ 200
Money::avg($collection, money('400'));  // $ 250
```

```php
use App\Models\Order;
use PostScripton\Money\Money;

$revenueCollection = Order::query()
    ->whereBetween('created_at', [
        now()->startOfHour(),
        now()->endOfHour(),
    ])
    ->pluck('revenue');

$avgRevenue = Money::avg($revenueCollection);
```

---

📌 Back to the [contents](/docs/04_money/README.md).
