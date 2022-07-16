# ⚙️ Settings
If you want to customize settings for your Money object, you need to specify settings for it.

To set setting:
```php
// Method #1
$money = money('12345000', settings());

// Method #2
$money = money('12345000');
$settings = settings(); // customize it as you want
$money->bind($settings);

// Method #3
$money = money('12345000'); // Every Money object has settings by default even if it is not provided
```

To get settings:
```php
$money = money('12345000');
$money->settings();
```

❗ **NOTE**
All the settings that are not provided or not changed will have default values, which were configured in the config file.

---

### Following settings are provided:
1. [Decimals](/docs/02_settings/decimals.md)
2. [Thousands separator](/docs/02_settings/thousands_separator.md)
3. [Decimal separator](/docs/02_settings/decimal_separator.md)
4. [Ends with zero](/docs/02_settings/ends_with_zero.md)
5. [Space between currency and amount](/docs/02_settings/space_between.md)
6. [Currency](/docs/02_settings/currency.md)

---

📌 Back to the [contents](/README.md#table-of-contents).
