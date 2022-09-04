# Display
You may specify the way to display the currency whether it will be as a symbol or iso-code.

## Constants

1. `Currency::DISPLAY_SYMBOL` (displays a currency as an ISO-code `$`)
2. `Currency::DISPLAY_CODE` (displays a currency as a symbol `USD`)

## Methods

### `getDisplay()`
**Returns**: `int` - one of the constants.

### `setDisplay([int $display = Currency::DISPLAY_SYMBOL])`
**Parameters**:
1. `[int $display = Currency::DISPLAY_SYMBOL]` (*optional*) - one of the constants.

**Returns**: `void`

## Usage

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Enums\CurrencyPosition;

$money = money('1234000');

$money->getCurrency()->getDisplay();    // 10 (Currency::DISPLAY_SYMBOL)
$money->toString();                     // "$ 123.4"

$money->getCurrency()->setDisplay(Currency::DISPLAY_CODE);

$money->getCurrency()->getDisplay();    // 11 (Currency::DISPLAY_CODE)
$money->toString();                     // "USD 123.4"

// If you don't like the look of the iso-code at the beginning
$money->getCurrency()->setPosition(CurrencyPosition::End);
$money->toString();                     // "123.4 USD"
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
