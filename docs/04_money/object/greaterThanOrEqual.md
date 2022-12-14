# `greaterThanOrEqual()`

is the current monetary object greater than or equal to the given one?

## Methods

### `greaterThanOrEqual(Money $money)`
**Parameters**:
1. `Money $money` - Money being compared.

**Returns**: `bool`

## Exceptions

1. `MoneyHasDifferentCurrenciesException` - is thrown when a `$money` argument has a different currency.

## Usage

```php
$m1 = money('1000000');
$m2 = money('500000');
$m3 = money('1000000');
$m4 = money('5000000');
$m5 = money('5000000', 'RUB');

$m1->greaterThanOrEqual($m2); // true
$m1->greaterThanOrEqual($m3); // true
$m1->greaterThanOrEqual($m4); // false
$m1->greaterThanOrEqual($m5); // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
