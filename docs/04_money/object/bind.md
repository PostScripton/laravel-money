# `bind()`
binds the Money object with the given settings.

## Methods

### `bind(MoneySettings $settings)`
**Parameters**:
1. `MoneySettings $settings` - the settings object that will be applied to the money object.

**Returns**: `Money`

## Usage

```php
$money = money(1234);
$settings = settings(); // customize it as you want
$money->bind($settings);
```

The bond to the old settings will be lost.

---

📌 Back to the [contents](/docs/04_money/README.md).
