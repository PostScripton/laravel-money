# `rebase()`

rebases the current monetary object on another money's amount.

## Methods

### `rebase(Money $money)`
**Parameters**:
1. `Money $money` - A monetary object to which the current money will be rebased.

**Returns**: `Money`

## Usage

```php
$m1 = money('1000000');                 // "$ 100"
$m2 = money('750000');                  // "$ 75"
$m3 = money('750000', currency('RUB')); // "75 ₽"

$m1->rebase($m2);                       // "$ 75"
$m1->rebase($m3);                       // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
