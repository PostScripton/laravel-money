# `upload()`

converts the money into the number according to origin for storing in a database.
It helps you to save the money back into your database because it gives you a certain number according to the way your database store money data.

There are some settings that effect the result:
1. [Number of decimals](/docs/02_Settings/decimals.md)
2. [Origin](/docs/02_Settings/origin.md)

## Methods

### `upload()`
**Returns**: `int|float`

## Usage

```php
use PostScripton\Money\MoneySettings;

$settings = settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

$int = money(1234.567890);
$float = money(1234.567890, $settings);

$int->upload();     // 12345
$float->upload();   // 1234.5
```

---

📌 Back to the [contents](/README.md#table-of-contents).