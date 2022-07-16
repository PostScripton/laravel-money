# `divide()`

divides an amount from the money.

## Methods

### `divide(float $number)`
**Parameters**:
1. `float $number` - a number on which the money will be divided.

**Returns**: `Money`

## Usage

```php
$money = money('1000000');  // "$ 100"
$money->divide(2);          // "$ 50"
```

---

📌 Back to the [contents](/docs/04_money/README.md).
