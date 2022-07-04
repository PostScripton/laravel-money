# `lessThanOrEqual()`

is the current monetary object less than or equal to the given one?

## Methods

### `lessThanOrEqual(Money $money)`
**Parameters**:
1. `Money $money` - Money being compared.

**Returns**: `bool`

## Usage

```php
$m1 = money(500);
$m2 = money(500);
$m3 = money(1000);
$m4 = money(100);

$m1->lessThanOrEqual($m2); // true
$m1->lessThanOrEqual($m3); // true
$m1->lessThanOrEqual($m4); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
