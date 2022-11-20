# `Money::setFormatter()`

sets a default formatter that will be applied by default to all monetary objects.

## Usage

```php
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Money;

$newFormatter = (new DefaultMoneyFormatter())
    ->useCurrency()
    ->displayCurrencyAs(CurrencyDisplay::Code)
    ->spaceBetweenCurrencyAndAmount()
    ->thousandsSeparator('')
    ->decimalSeparator('.')
    ->decimals(2)
    ->endsWithZero();

Money::setFormatter($newFormatter);

$money = money_parse('$ 1234.5');
$money->toString(); // "USD 1234.50"
```

---

👀 See [here](/docs/02_formatting/README.md) for full details.

📌 Back to the [contents](/docs/04_money/README.md).
