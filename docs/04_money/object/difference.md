# `difference()`

shows the difference between two money objects.

## Methods

### `difference(Money $money, [?MoneySettings $settings = null])`
**Parameters**:
1. `Money $money` - the given money must be the same currency as the first one.
2. `[?MoneySettings $settings = null]` (*optional*) - settings for displaying the difference.

**Returns**: `string` - formatted amount.

## Exceptions

1. `MoneyHasDifferentCurrenciesException` - is thrown when a `$money` argument has a different currency.

## Usage

```php
$m1 = money(500);
$m2 = money(1000);

$m1->difference($m2); // "$ -50"
```

```php
$m1 = money(500);
$m2 = money(1000, currency('RUB'));

$m1->difference($m2); // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
