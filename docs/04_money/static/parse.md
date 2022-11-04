# `Money::parse()`

parses the string and turns it into a money instance.

## Methods

### `Money::parse(string $money, [?string $currencyCode = null])`
**Parameters**:
1. `string $money` - a money string to be parsed.
2. `[?string $currencyCode = null]` (*optional*) - you help the parser to recognize a currency by providing a code (`USD`/`840`).
    If null is provided then `default_currency` from the config file is used.
    Currencies will be chosen from your currency list from the config file.

**Returns**: `Money`

## Exceptions

1. `Exception` - is thrown when the given string can not be parsed at all.
2. `CurrencyDoesNotExistException` - is thrown when a currency you provide is not included in a currency list.

## Usage

```php
use PostScripton\Money\Money;

// Simply parse an amount with the default currency.

Money::parse('1 234.5678');
money_parse('1 234.5678');

// Or parse an amount with a currency

money_parse('$100');
money_parse('$100');
money_parse('€ 100', 'EUR');
money_parse('£-100', 'GBP');
money_parse('GBP -100', 'GBP');
money_parse('-100 ₽', 'RUB');
money_parse('-100 RUB', '643');
```

Unknown currency throws the exception because this currency is not expected.
```php
use PostScripton\Money\Money;

Money::parse('# 100'); // Exception
```

👀 See [here](/tests/Unit/ParserTest.php) for all the cases in tests.

---

📌 Back to the [contents](/docs/04_money/README.md).
