# Currencies collection methods

The class `Currencies` is used in order to operate over a collection of currencies.

## Get current currencies

Get all the chosen (current) currencies.

### Methods

#### `Currencies::get()`
**Returns**: `Illuminate\Support\Collection`

#### `Currencies::getCodesArray()`
**Returns**: `array` as `["USD", "EUR", ...]`

### Usage

```php
use PostScripton\Money\Currencies;

Currencies::get();              // Collection of currencies
Currencies::getCodesArray();    // ["USD", "EUR", ...]
```

## All currencies are the same

### Methods

#### `Currencies::same(Currency|string ...$currencies)`
**Returns**: `bool`

### Usage

```php
use PostScripton\Money\Currencies;

// Always true because <= 1 elements
Currencies::same();                                 // true
Currencies::same(currency('USD'));                  // true

Currencies::same('USD', currency('USD'));           // true
Currencies::same(currency('USD'), 'USD', 'RUB');    // false
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
