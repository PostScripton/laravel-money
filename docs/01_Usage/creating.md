# 🧰 Creating
In order to use this package, you need to create a Money object:

```php
use PostScripton\Money\Money;

$money = new Money(1234);
$money = Money::make(1234);
$money = money(1234); // preferred variant
```

You can apply the following parameters for any of the variants above:
```php
$money = money(1234, currency('RUB'));
$money = money(1234, settings());
$money = money(1234, currency('RUB'), settings());

// NOT: money(1234, settings(), currency('RUB'))
```

---

📌 Back to the [contents](/README.md#table-of-contents).