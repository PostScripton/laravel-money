# 💵 Currency
You may get or specify currency for money `($) 123.4`.

## Methods

### `getCurrency()`
**Returns**: `PostScripton\Money\Currency`

### `setCurrency(Currency $currency)`
**Parameters**:
1. `Currency $currency` - a currency to set.

**Returns**: `void`

## Usage

```php
$money = money(1234);

$money->settings()->getCurrency();              // PostScripton\Money\Currency class
$money->settings()->getCurrency()->getSymbol(); // "$"
$money->toString();                             // "$ 123.4"

$money->settings()->setCurrency(currency('RUB'));

$money->settings()->getCurrency();              // PostScripton\Money\Currency class
$money->settings()->getCurrency()->getSymbol(); // "₽"
$money->toString();                             // "123.4 ₽"
```

There is a shortcut for getting a currency:
```php
$money = money(1234);
$money->getCurrency(); // the same as: $money->settings()->getCurrency()
```

---

📌 Back to the [contents](/README.md#table-of-contents).