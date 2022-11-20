# 🎨️ Formatting

There are several ways to convert a monetary object into a string.

Behind the scenes, formatters are used to do this. All formatters implement the `MoneyFormatter` interface.

## Default formatter

By default, for all monetary objects use `DefaultMoneyFormatter`,
which is, by the way, **configured with values from the config file** each time you create it.

You can replace a default formatter with your own by calling `Money::setFormatter()` method.

```php
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Money;

$formatterForAllMonetaryObjects = (new DefaultMoneyFormatter())
    ->useCurrency()
    ->displayCurrencyAs(CurrencyDisplay::Code)
    ->spaceBetweenCurrencyAndAmount()
    ->thousandsSeparator('')
    ->decimalSeparator(',')
    ->decimals(4);

Money::setFormatter($formatterForAllMonetaryObjects);

money_parse('1 234.56')->toString(); // "USD 1234,5600"
```

## One-time-formatter

In case you want to apply your configured formatter only for a particular monetary object, you can pass it to the `toString()` method.

```php
use PostScripton\Money\Formatters\DefaultMoneyFormatter;

$yourOwnFormatter = (new DefaultMoneyFormatter())
    ->dontUseCurrency()
    ->thousandsSeparator('')
    ->decimals(4);

money_parse('1 234.56')->toString($yourOwnFormatter);   // "1234.5600"
money_parse('1 234.56')->toString();                    // "$ 1 234.56"
```

## Other "to string" methods

There are several pre-defined methods for displaying monetary objects for most cases.

```php
$money = money_parse('1 234.56');

// Uses formatter that is configured from the config file
// or is set via setFormatter() method
$money->toString();             // "$ 1 234.6" (depends on your config file)

$money->toAmountOnlyString();   // "1 234.6" (like above but without currency)

$money->toDecimalString(2);     // "1234.56"

$money->toFinanceString(2);     // "$ 1234.56"
```

Don't hesitate to suggest a new method, that you think most people will use, and probably it will be released in further versions.

---

📌 Back to the [contents](/README.md#table-of-contents).
