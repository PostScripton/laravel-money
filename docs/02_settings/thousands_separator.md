# Thousands separator
You may get or specify a separator between thousands `1(.)000(.)000`:

## Methods

### `getThousandsSeparator()`
**Returns**: `string`

### `setThousandsSeparator(string $separator)`
**Parameters**:
1. `string $separator` - a separator between thousands `1.000.000 => .`.

**Returns**: `void`

## Usage

```php
$money = money('10000000000');

$money->settings()->getThousandsSeparator();    // " "
$money->toString();                             // "$ 1 000 000"

$money->settings()->setThousandsSeparator("'"); 

$money->settings()->getThousandsSeparator();    // "'"
$money->toString();                             // "$ 1'000'000"
```

---

📌 Back to the [contents](/docs/02_settings/README.md).
