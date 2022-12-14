# `add()`

adds another monetary object to the current one.

## Methods

### `add(Money $money)`
**Parameters**:
1. `Money $money` - an amount or Money that will be added.

**Returns**: `Money`

## Exceptions

1. `MoneyHasDifferentCurrenciesException` - is thrown when a `$money` argument has a different currency.

## Usage

```php
$m1 = money('1000000');                 // "$ 100"
$m2 = money('500000');                  // "$ 50"
$m3 = money('500000', currency('RUB')); // "50 ₽"

$m1->add($m2);                          // "$ 150"
$m1->add($m3);                          // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
