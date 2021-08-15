# Decimal separator
You may get or specify a separator for decimals `123(.)4`:

## Methods

### `getDecimalSeparator()`
**Returns**: `string`

### `setDecimalSeparator(string $separator)`
**Parameters**:
1. `string $separator` - a decimal separator `123.4 => .`.

**Returns**: `void`

## Usage

```php
$money = money(1234);

$money->settings()->getDecimalSeparator();  // "."
$money->toString();                         // "$ 123.4"

$money->settings()->setDecimalSeparator(","); 

$money->settings()->getDecimalSeparator();  // ","
$money->toString();                         // "$ 123,4"
```

---

📌 Back to the [contents](/docs/02_settings/README.md).
