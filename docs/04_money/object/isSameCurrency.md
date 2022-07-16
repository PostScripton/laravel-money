# `isSameCurrency()`

checks whether two money objects have the same currency.

## Methods

### `isSameCurrency(Money $money)`
**Parameters**:
1. `Money $money`

**Returns**: `bool`

## Usage

```php
$m1 = money('1000000');
$m2 = money('5000000');
$m3 = money('5000000', currency('RUB'));

$m1->isSameCurrency($m2); // true
$m1->isSameCurrency($m3); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
