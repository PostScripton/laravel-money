# `greaterThanOrEqual()`

is the current monetary object greater than or equal to the given one?

## Methods

### `greaterThanOrEqual(Money $money)`
**Parameters**:
1. `Money $money` - Money being compared.

**Returns**: `bool`

## Usage

```php
$m1 = money(1000);
$m2 = money(500);
$m3 = money(1000);
$m4 = money(5000);

$m1->greaterThanOrEqual($m2); // true
$m1->greaterThanOrEqual($m3); // true
$m1->greaterThanOrEqual($m4); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
