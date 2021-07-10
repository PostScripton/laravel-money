# `lessThan()`

сompares with an amount, or a money object whether it is less than the number, or the money object.

## Methods

### `lessThan($money, [int $origin = MoneySettings::ORIGIN_INT])`
**Parameters**:
1. `$money` - an amount or a money object.
2. `[int $origin = MoneySettings::ORIGIN_INT]` (*optional*) - one of the [constants](/docs/02_Settings/origin.md#constants).

**Returns**: `bool`

## Usage

### Less than int amount

```php
$money = money(500);

$money->lessThan(1000); // true
$money->lessThan(500);  // false
```

### Less than float amount

```php
use PostScripton\Money\MoneySettings;

$money = money(500);

$money->lessThan(100.0, MoneySettings::ORIGIN_FLOAT); // true
$money->lessThan(50.0, MoneySettings::ORIGIN_FLOAT);  // false
```

### Less than money object

```php
$m1 = money(500);
$m2 = money(500);
$m3 = money(1000);

$m1->lessThan($m3); // true
$m1->lessThan($m2); // false
```

---

📌 Back to the [contents](/README.md#table-of-contents).