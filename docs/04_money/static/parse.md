# `Money::parse()`

parses the string and turns it into a money instance.

## Methods

### `Money::parse(string $money)`
**Parameters**:
1. `string $money` - a money string to be parsed.

**Returns**: `Money`

## Exceptions

1. `WrongParserStringException` - is thrown when the given string can not be parsed at all.

## Usage

Parses popular separators
```php
use PostScripton\Money\Money;

// thousands: [ " ", ".", ",", "'" ]
// decimals: [ ".", "," ]

Money::parse("$ 1 234")
Money::parse("$ 1.234")
Money::parse("$ 1,234")
Money::parse("$ 1'234")

Money::parse("$ 123.4")
Money::parse("$ 123,4")
```

Knows the most popular currencies
```php
use PostScripton\Money\Money;

// [ USD, EUR, JPY, GBP, AUD, CAD, CHF, RUB, UAH, BYN ]

Money::parse('100 $');
Money::parse('100€');
Money::parse('-100¥');
Money::parse('-100 £');
Money::parse('Fr.100');
Money::parse('₽ -100');
Money::parse('₴ 100');
Money::parse('100 AUD');
Money::parse('CAD 100');
Money::parse('RUB -100');
Money::parse('-100 BYN');


// If you want Australian, Canadian or any other dollar, you should specify ISO-code,
// otherwise it would be parsed as default currency.
Money::parse('AUD 100');
Money::parse('100 CAD');
```

Unknown currency interprets as the default one
```php
use PostScripton\Money\Money;

// will be USD because it is default currency
Money::parse('# 100');
Money::parse('100 #');
```

---

📌 Back to the [contents](/docs/04_money/README.md).
