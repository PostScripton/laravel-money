# Decimals
You may get or specify a number of decimals `123.(4)`:

## Methods

### `getDecimals()`
**Returns**: `int`

### `setDecimals([int $decimals = 1])`
**Parameters**:
1. `[int $decimals = 1]` (*optional*) - a number of decimals after the separator `123.4 => 1`.

**Returns**: `void`

## Usage

```php
$money = money(1234);

$money->settings()->getDecimals();  // 1
$money->toString();                 // "$ 123.4"

$money->settings()->setDecimals(2); 

$money->settings()->getDecimals();  // 2
$money->toString();                 // "$ 12.34" 
```

---

📌 Back to the [contents](/docs/02_settings/README.md).
