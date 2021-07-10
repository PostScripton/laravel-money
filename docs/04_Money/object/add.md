# `add()`

adds an amount, or a money object to the money.

## Methods

### `add($money, int $origin = MoneySettings::ORIGIN_INT)`
**Parameters**:
1. `int|float|Money $money` - an amount or Money that will be added.
2. `[int $origin = MoneySettings::ORIGIN_INT]` - one of the [constants](/docs/02_Settings/origin.md#constants).

**Returns**: `Money`

## Usage

### Add int amount

```php
$money = money(1000);   // "$ 100"
$money->add(500);       // "$ 150"
```

### Add float amount

```php
use PostScripton\Money\MoneySettings;

$money = money(1000);                           // "$ 100"
$money->add(50.0, MoneySettings::ORIGIN_FLOAT); // "$ 150"
```

### Add money object

```php
$m1 = money(1000);                              // "$ 100"
$m2 = money(500);                               // "$ 50"
$m3 = money(500, currency('RUB'));              // "50 ₽"

$m1->add($m2);                                  // "$ 150"
$m1->add($m3);                                  // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/README.md#table-of-contents).