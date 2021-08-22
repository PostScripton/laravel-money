# Currency List
If you wish, you may select another currency list.
To select currency list by default, go to the `config/money.php` and find there `currency_list`.

All the lists are located at `vendor/postscripton/money/src/List`, so if you want, you can check something up there.

## Constants

1. `Currency::LIST_ALL` (almost all the currencies in the world)
2. `Currency::LIST_POPULAR` (the most traded currencies in the world)
3. `Currency::LIST_CUSTOM` (only custom currencies)
4. `Currency::LIST_CONFIG` (returns to list what you've written in the config)

## Methods

### `setCurrencyList([string $list = Currency::LIST_POPULAR])`
**Parameters**:
1. `[string $list = Currency::LIST_POPULAR]` (*optional*) - one of the constants of the lists of currencies.

**Returns**: `void`

## Usage

```php
use PostScripton\Money\Currency;

Currency::setCurrencyList(Currency::LIST_POPULAR);
Currency::code('USD');

Currency::setCurrencyList(Currency::LIST_ALL);
Currency::code('EGP'); // now available
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
