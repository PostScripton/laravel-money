# `lessThanOrEqual()`

сompares with an amount, or a money object whether it is less than or equals to the number, or the money object.

## Methods

### `lessThanOrEqual($money, [int $origin = MoneySettings::ORIGIN_INT])`
**Parameters**:
1. `$money` - an amount or a money object.
2. `[int $origin = MoneySettings::ORIGIN_INT]` (*optional*) - one of the [constants](/docs/02_Settings/origin.md#constants).

**Returns**: `bool`

## Usage

### Less than or equal to int amount

```php
$money = money(500);

$money->lessThanOrEqual(1000); // true
$money->lessThanOrEqual(500);  // true
$money->lessThanOrEqual(100);  // false
```

### Less than or equal to float amount

```php
use PostScripton\Money\MoneySettings;

$money = money(500);

$money->lessThanOrEqual(100.0, MoneySettings::ORIGIN_FLOAT); // true
$money->lessThanOrEqual(50.0, MoneySettings::ORIGIN_FLOAT);  // true
$money->lessThanOrEqual(10.0, MoneySettings::ORIGIN_FLOAT);  // false
```

### Less than or equal to money object

```php
$m1 = money(500);
$m2 = money(500);
$m3 = money(1000);
$m4 = money(100);

$m1->lessThanOrEqual($m2); // true
$m1->lessThanOrEqual($m3); // true
$m1->lessThanOrEqual($m4); // false
```

---

📌 Back to the [contents](/README.md#table-of-contents).