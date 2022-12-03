# 🧰 Creating
In order to use this package, you need to create a monetary object:

```php
use PostScripton\Money\Money;

$money = new Money('12345000');
$money = Money::of('12345000');
$money = money('12345000'); // preferred variant
```

You can specify currency as second argument:

```php
use PostScripton\Money\Currency;

$money = new Money('12345000'); // if you don't specify then default is used
$money = Money::of('12345000', Currency::code('RUB'));
$money = money('12345000', currency('RUB'));
```

## Important

It is important to pass **string-integer** as amount for two reasons:

1. Non-numeric string throws `InvalidArgumentException`
2. All decimals get trimmed

```php
$money = money('qwerty');   // InvalidArgumentException

$money = money('12345000.1234567890');
$money->getAmount();        // "12345000"
```

---

📌 Back to the [contents](/README.md#table-of-contents).
