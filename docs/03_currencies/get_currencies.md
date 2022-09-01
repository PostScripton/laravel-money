# Get current currencies

Get all the chosen (current) currencies.

## Methods

### `Currency::getCurrencies()`
**Returns**: `Illuminate\Support\Collection`

### `Currency::getCurrencyCodesArray()`
**Returns**: `array` as `["USD", "EUR", ...]`

## Usage

```php
use PostScripton\Money\Currency;

Currency::getCurrencies();          // Collection of currencies
Currency::getCurrencyCodesArray();  // ["USD", "EUR", ...]
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
