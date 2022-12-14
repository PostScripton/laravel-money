# `lessThanOrEqual()`

is the current monetary object less than or equal to the given one?

## Methods

### `lessThanOrEqual(Money $money)`
**Parameters**:
1. `Money $money` - Money being compared.

**Returns**: `bool`

## Exceptions

1. `MoneyHasDifferentCurrenciesException` - is thrown when a `$money` argument has a different currency.

## Usage

```php
$m1 = money('500000');
$m2 = money('500000');
$m3 = money('1000000');
$m4 = money('100000');
$m5 = money('100000', 'RUB');

$m1->lessThanOrEqual($m2); // true
$m1->lessThanOrEqual($m3); // true
$m1->lessThanOrEqual($m4); // false
$m1->lessThanOrEqual($m5); // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
