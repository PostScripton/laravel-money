# `upload()`

converts the money into the number according to origin for storing in a database.
It helps you to save the money back into your database because it gives you a certain number according to the way your database store money data.

> Uploading depends on the `origin` value from the config file.

There are some settings that effect the result:
1. [Number of decimals](/docs/02_settings/decimals.md)
2. [Origin](/docs/02_settings/origin.md)

## Methods

### `upload()`
**Returns**: `int|float`

## Usage

```php
use PostScripton\Money\MoneySettings;

// config:
// origin => MoneySettings::ORIGIN_INT

$settings = settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

$int = money(1234.567890);
$float = money(1234.567890, $settings);

$int->upload();     // 1234
$float->upload();   // 12345
```

```php
use PostScripton\Money\MoneySettings;

// config:
// origin => MoneySettings::ORIGIN_FLOAT

$settings = settings()->setOrigin(MoneySettings::ORIGIN_INT);

$int = money(1234.567890, $settings);
$float = money(1234.567890);

$int->upload();     // 123.4
$float->upload();   // 1234.5
```

---

📌 Back to the [contents](/docs/04_money/README.md).
