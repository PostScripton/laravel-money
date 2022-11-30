# 🧰 Creating
In order to use this package, you need to create a Money object:

```php
use PostScripton\Money\Money;

$money = new Money('12345000');
$money = Money::of('12345000');
$money = money('12345000'); // preferred variant
```

You can apply the following parameters for any of the variants above:
```php
$money = money('12345000', currency('RUB'));
$money = money('12345000', settings());
$money = money('12345000', currency('RUB'), settings());

// NOT: money('12345000', settings(), currency('RUB'))
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
