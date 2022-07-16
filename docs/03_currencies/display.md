# Display
You may specify the way to display the currency whether it will be as an iso-code or a symbol.

## Constants

1. `Currency::DISPLAY_SYMBOL` (displays a currency as an ISO-code `USD`)
2. `Currency::DISPLAY_CODE` (displays a currency as a symbol `$`)

## Methods

### `setCurrencyList([string $list = Currency::LIST_POPULAR])`
**Parameters**:
1. `[string $list = Currency::LIST_POPULAR]` (*optional*) - one of the constants of the lists of currencies.

**Returns**: `void`

## Usage

```php
use PostScripton\Money\Currency;

$money = money('1234000');

$money->settings()->getCurrency()->getDisplay();    // 10 (Currency::DISPLAY_SYMBOL)
$money->toString();                                 // "$ 123.4"

$money->settings()->getCurrency()->setDisplay(Currency::DISPLAY_CODE);

$money->settings()->getCurrency()->getDisplay();    // 11 (Currency::DISPLAY_CODE)
$money->toString();                                 // "USD 123.4"

// If you don't like the look of the iso-code at the beginning
$money->settings()->getCurrency()->setPosition(Currency::POSITION_END);
$money->toString();                                 // "123.4 USD"
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
