# Get current currencies

Get all the chosen (current) currencies.

## Methods

### `Currencies::get()`
**Returns**: `Illuminate\Support\Collection`

### `Currencies::getCodesArray()`
**Returns**: `array` as `["USD", "EUR", ...]`

## Usage

```php
use PostScripton\Money\Currencies;

Currencies::get();              // Collection of currencies
Currencies::getCodesArray();    // ["USD", "EUR", ...]
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
