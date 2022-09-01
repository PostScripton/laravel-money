# Information

## Methods

### `getFullName()`
**Returns**: `string` - a full name of a currency `United States dollar`.

### `getName()`
**Returns**: `string` - a short name of a currency `dollar`.

### `getCode()`
**Returns**: `string` - an ISO-code `USD`.

### `getNumCode()`
**Returns**: `string` - a numeric code `840`.

### `getSymbol([?int $index = null])`
**Parameters**:
1. `[?int $index = null]` (*optional*) - if there are more than one symbol (array of symbols), then you should pass an index of the array of symbols, otherwise the first one is taken.

**Returns**: `string` - a symbol `$`.

### `getPosition()`
**Returns**: `int` - one of [the constants](/docs/03_currencies/position.md#constants).

### `getDisplay()`
**Returns**: `int` - one of [the constants](/docs/03_currencies/display.md#constants).

## Usage

```php
$usd = currency("USD");

$usd->getFullName();    // "United States dollar"
$usd->getName();        // "dollar"
$usd->getCode();        // "USD"
$usd->getNumCode();     // "840"
$usd->getSymbol();      // "$"
$usd->getSymbols();     // ["$"]
$usd->getPosition();    // 0 (Currency::POSITION_START)
$usd->getDisplay();     // 10 (Currency::DISPLAY_SYMBOL)
```

`getSymbol()` takes an index as its first parameter only if there are more than one symbol for the currency.

```php
use PostScripton\Money\Currency;

$currency = currency("EGP");

// ["£", "ج.م"]
$currency->getSymbol();     // "£"
$currency->getSymbol(1);    // "ج.م"
```

See about [choosing a preferred symbol](/docs/03_currencies/preferred_symbol.md) for a currency with multiple symbols as well.

---

📌 Back to the [contents](/docs/03_currencies/README.md).
