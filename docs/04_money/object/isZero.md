# `isZero()`

checks whether the money's number is zero.

## Methods

### `isZero()`
**Returns**: `bool`

## Usage

```php
$m1 = money('0');
$m2 = money('1000000');
$m3 = money('-10000000');

$m1->isZero(); // true
$m2->isZero(); // false
$m3->isZero(); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
