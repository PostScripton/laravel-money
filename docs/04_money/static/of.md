# `Money::of()`
creates a monetary object.

## Usage

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

// Default currency: USD

Money::of('0');                                 // "$ 0"
Money::of('1230000');                           // "$ 123"
Money::of('1234000');                           // "$ 123.4"
Money::of('12345000');                          // "$ 1 234.5"

Money::of('12345000', Currency::code('RUB'));   // "1 234.5 ₽"
Money::of('12345000', 'RUB');                   // "1 234.5 ₽"
```

Method `of()` is one of the variants to create a monetary object.
All the ways to pass parameters have already been discussed [here](/docs/01_usage/creating.md).

---

📌 Back to the [contents](/docs/04_money/README.md).
