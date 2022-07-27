# 💲 Currency
You may get or specify currency for money `($) 123.4`.

## Methods

### `getCurrency()`
**Returns**: `Currency`

### `setCurrency(Currency $currency)`
**Parameters**:
1. `Currency $currency` - a currency to set.

**Returns**: `Money`

## Usage

```php
$money = money('12345000');

$money->getCurrency();              // PostScripton\Money\Currency class
$money->getCurrency()->getSymbol(); // "$"
$money->toString();                 // "$ 1 234.5"

$money->setCurrency(currency('RUB'));

$money->getCurrency();              // PostScripton\Money\Currency class
$money->getCurrency()->getSymbol(); // "₽"
$money->toString();                 // "1 234.5 ₽"
```

---

📌 Back to the [contents](/docs/04_money/README.md).
