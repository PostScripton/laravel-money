# `lessThan()`

is the current monetary object less than the given one?

## Methods

### `lessThan(Money $money)`
**Parameters**:
1. `Money $money` - Money being compared.

**Returns**: `bool`

## Usage

```php
$m1 = money('500000');
$m2 = money('500000');
$m3 = money('1000000');

$m1->lessThan($m3); // true
$m1->lessThan($m2); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
