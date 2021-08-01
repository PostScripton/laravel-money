# Get current currencies

Get all the chosen (current) currencies.

## Methods

### `Currency::getCurrencies`
**Returns**: `array` as `["USD", "EUR", ...]`

## Usage

```php
use PostScripton\Money\Currency;

Currency::setCurrencyList(Currency::LIST_POPULAR);
Currency::getCurrencies(); // ["USD", "EUR", ...]
```

---

📌 Back to the [contents](/README.md#table-of-contents).
