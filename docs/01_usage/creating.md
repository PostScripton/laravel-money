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

---

📌 Back to the [contents](/README.md#table-of-contents).
