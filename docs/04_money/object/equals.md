# `equals()`

checks whether two monetary objects are equal or not.

## Methods

### `equals(Money $money, [bool $strict = true])`
**Parameters**:
1. `Money $money`
2. `[bool $strict = true]` (*optional*) - whether to check currencies or not.

**Returns**: `bool`

## Usage

```php
$m1 = money('12345000');
$m2 = money('12345000');
$m3 = money('12345000', currency('RUB'));

$m1->equals($m2); // true
$m1->equals($m3); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
