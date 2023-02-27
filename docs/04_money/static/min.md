# `Money::min()`

finds the minimum money out of a sequence of monetary objects.

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

Money::min();                           // null
Money::min(collect());                  // null
Money::min($m1, $m2, $m3);              // $ 100
Money::min($collection);                // $ 100
Money::min($collection, money('400'));  // $ 100
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

$minRevenue = Money::min($revenueCollection);
```

---

📌 Back to the [contents](/docs/04_money/README.md).
