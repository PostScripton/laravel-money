# Preferred symbol

When there is an array of symbols in a currency, and you always want to choose the second one without specifying this, you can set your preferred symbol.

## Methods

### `setPreferredSymbol([int $index = 0])`
**Parameters**:
1. `[int $index = 0]` (*optional*) - index of the array.

**Returns**: `Currency`

## Usage

```php
use PostScripton\Money\Currency;

Currency::setCurrencyList(Currency::LIST_ALL);

$currency = currency("EGP");
$currency->setPreferredSymbol(1);

// ["£", "ج.م"]
$currency->getSymbol();     // "ج.م"
$currency->getSymbol(0);    // "£"
$currency->getSymbol(1);    // "ج.م"
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
