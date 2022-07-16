# `isPositive()`

checks whether the money's number is positive (greater than zero).

## Methods

### `isPositive()`
**Returns**: `bool`

## Usage

```php
$m1 = money('1000000');
$m2 = money('-1000000');

$m1->isPositive(); // true
$m2->isPositive(); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
