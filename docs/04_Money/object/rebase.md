# `rebase()`

rebases the money on an amount.

## Methods

### `rebase($money, int $origin = MoneySettings::ORIGIN_INT)`
**Parameters**:
1. `int|float|Money $money` - an amount or Money to which the money will be rebased.
2. `[int $origin = MoneySettings::ORIGIN_INT]` - one of the [constants](/docs/02_Settings/origin.md#constants).

**Returns**: `Money`

## Usage

### Rebase on int amount

```php
$money = money(1500);   // "$ 150"
$money->rebase(100);    // "$ 10"
```

### Rebase on float amount

```php
use PostScripton\Money\MoneySettings;

$money = money(1500);                               // "$ 150"
$money->rebase(10.0, MoneySettings::ORIGIN_FLOAT);  // "$ 10"
```

### Rebase on money object

```php
$m1 = money(1000);                  // "$ 100"
$m2 = money(750);                   // "$ 75"
$m3 = money(750, currency('RUB'));  // "75 ₽"

$m1->rebase($m2);                   // "$ 75"
$m1->rebase($m3);                   // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/README.md#table-of-contents).