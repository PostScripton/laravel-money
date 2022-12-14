# `subtract()`

subtracts another monetary object from the current one.

## Methods

### `subtract(Money $money)`
**Parameters**:
1. `Money $money` - Money that will be subtracted.

**Returns**: `Money`

## Exceptions

1. `MoneyHasDifferentCurrenciesException` - is thrown when a `$money` argument has a different currency.

## Usage

```php
$m1 = money('1500000');                 // "$ 150"
$m2 = money('500000');                  // "$ 50"
$m3 = money('500000', currency('RUB')); // "50 ₽"

$m1->subtract($m2);                     // "$ 100"
$m1->subtract($m3);                     // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
