# `greaterThan()`

is the current monetary object greater than the given one?

## Methods

### `greaterThan(Money $money)`
**Parameters**:
1. `Money $money` - Money being compared.

**Returns**: `bool`

## Usage

```php
$m1 = money('1000000');
$m2 = money('1000000');
$m3 = money('500000');

$m1->greaterThan($m3); // true
$m1->greaterThan($m2); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
