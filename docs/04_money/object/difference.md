# `difference()`

shows the difference between two monetary objects.

In fact, this method is an alias to `->clone()->subtract()->absolute()`

## Methods

### `difference(Money $money)`
**Parameters**:
1. `Money $money` - the given money must be the same currency as the first one.

**Returns**: `Money`

## Exceptions

1. `MoneyHasDifferentCurrenciesException` - is thrown when a `$money` argument has a different currency.

## Usage

```php
$m1 = money('500000');
$m2 = money('1000000');

$m3 = $m1->difference($m2); // $ 50

// the above is an alias to this
$m3 = $m1->clone()->subtract($m2)->absolute(); // $ 50
```

```php
$m1 = money('500000');
$m2 = money('1000000', currency('RUB'));

$m3 = $m1->difference($m2); // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
