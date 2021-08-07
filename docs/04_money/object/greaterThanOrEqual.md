# `greaterThanOrEqual()`

сompares with an amount, or a money object whether it is greater than or equals to the number, or the money object.

## Methods

### `greaterThanOrEqual($money, [int $origin = MoneySettings::ORIGIN_INT])`
**Parameters**:
1. `$money` - an amount or a money object.
2. `[int $origin = MoneySettings::ORIGIN_INT]` (*optional*) - one of the [constants](/docs/02_settings/origin.md#constants).

**Returns**: `bool`

## Usage

### Greater than or equal to int amount

```php
$money = money(1000);

$money->greaterThanOrEqual(500);   // true
$money->greaterThanOrEqual(1000);  // true
$money->greaterThanOrEqual(5000);  // false
```

### Greater than or equal to float amount

```php
use PostScripton\Money\MoneySettings;

$money = money(1000);

$money->greaterThanOrEqual(50.0, MoneySettings::ORIGIN_FLOAT);     // true
$money->greaterThanOrEqual(100.0, MoneySettings::ORIGIN_FLOAT);    // true
$money->greaterThanOrEqual(500.0, MoneySettings::ORIGIN_FLOAT);    // false
```

### Greater than or equal to money object

```php
$m1 = money(1000);
$m2 = money(500);
$m3 = money(1000);
$m4 = money(5000);

$m1->greaterThanOrEqual($m2); // true
$m1->greaterThanOrEqual($m3); // true
$m1->greaterThanOrEqual($m4); // false
```

---

📌 Back to the [contents](/README.md#table-of-contents).
