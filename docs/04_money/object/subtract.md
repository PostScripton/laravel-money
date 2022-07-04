# `subtract()`

subtracts another monetary object from the current one.

## Methods

### `subtract(Money $money)`
**Parameters**:
1. `Money $money` - Money that will be subtracted.

**Returns**: `Money`

## Usage

```php
$m1 = money(1500);                  // "$ 150"
$m2 = money(500);                   // "$ 50"
$m3 = money(500, currency('RUB'));  // "50 ₽"

$m1->subtract($m2);                 // "$ 100"
$m1->subtract($m3);                 // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
