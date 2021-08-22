# `subtract()`

subtracts an amount, or a money object from the money.

## Methods

### `subtract($money, int $origin = MoneySettings::ORIGIN_INT)`
**Parameters**:
1. `int|float|Money $money` - an amount or Money that will be subtracted.
2. `[int $origin = MoneySettings::ORIGIN_INT]` (*optional*) - one of the [constants](/docs/02_settings/origin.md#constants).

**Returns**: `Money`

## Usage

### Subtract int amount

```php
$money = money(1500);   // "$ 150"
$money->subtract(500);  // "$ 100"
```

### Subtract float amount

```php
use PostScripton\Money\MoneySettings;

$money = money(1500);                                   // "$ 150"
$money->subtract(50.0, MoneySettings::ORIGIN_FLOAT);    // "$ 100"
```

### Subtract money object

```php
$m1 = money(1500);                          // "$ 150"
$m2 = money(500);                           // "$ 50"
$m3 = money(500, currency('RUB'));          // "50 ₽"

$m1->subtract($m2);                         // "$ 100"
$m1->subtract($m3);                         // MoneyHasDifferentCurrenciesException
```

---

📌 Back to the [contents](/docs/04_money/README.md).
