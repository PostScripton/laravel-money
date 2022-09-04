# Display
You may specify the way to display the currency whether it will be as a symbol or iso-code.

## Methods

### `getDisplay()`
**Returns**: `CurrencyDisplay`

### `setDisplay([CurrencyDisplay $display = CurrencyDisplay::Symbol])`
**Parameters**:
1. `[CurrencyDisplay $display = CurrencyDisplay::Symbol]` (*optional*).

**Returns**: `Currency`

## Usage

```php
use PostScripton\Money\Enums\CurrencyPosition;

$money = money('1234000');

$money->getCurrency()->getDisplay();    // CurrencyDisplay::Symbol
$money->toString();                     // "$ 123.4"

$money->getCurrency()->setDisplay(CurrencyDisplay::Code);

$money->getCurrency()->getDisplay();    // CurrencyDisplay::Code
$money->toString();                     // "USD 123.4"

// If you don't like the look of the iso-code at the beginning
$money->getCurrency()->setPosition(CurrencyPosition::End);
$money->toString();                     // "123.4 USD"
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
