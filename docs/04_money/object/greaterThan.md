# `greaterThan()`

сompares with an amount, or a money object whether it is greater than the number, or the money object.

## Methods

### `greaterThan($money, [int $origin = MoneySettings::ORIGIN_INT])`
**Parameters**:
1. `$money` - an amount or a money object.
2. `[int $origin = MoneySettings::ORIGIN_INT]` (*optional*) - one of the [constants](/docs/02_settings/origin.md#constants).

**Returns**: `bool`

## Usage

### Greater than int amount

```php
$money = money(1000);

$money->greaterThan(500);   // true
$money->greaterThan(1000);  // false
```

### Greater than float amount

```php
use PostScripton\Money\MoneySettings;

$money = money(1000);

$money->greaterThan(50.0, MoneySettings::ORIGIN_FLOAT);     // true
$money->greaterThan(100.0, MoneySettings::ORIGIN_FLOAT);    // false
```

### Greater than money object

```php
$m1 = money(1000);
$m2 = money(1000);
$m3 = money(500);

$m1->greaterThan($m3); // true
$m1->greaterThan($m2); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
