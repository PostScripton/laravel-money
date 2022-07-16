# Ends with zero
You may get or specify whether money ends with 0 or not `100(.0)`:

## Methods

### `endsWith0()`
**Returns**: `bool`

### `setEndsWith0([bool $ends = false])`
**Parameters**:
1. `[bool $ends = false]` (*optional*) - determines whether the number ends with zero `100.0 => true`.

**Returns**: `void`

## Usage

```php
$money = money('1000000');

$money->settings()->endsWith0();    // false
$money->toString();                 // "$ 100"

$money->settings()->setEndsWith0(true); 

$money->settings()->endsWith0();    // true
$money->toString();                 // "$ 100.0"
```

---

📌 Back to the [contents](/docs/02_settings/README.md).
