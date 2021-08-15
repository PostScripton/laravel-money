# `Money::set()`
sets default settings for any Money object.

## Usage

```php
use PostScripton\Money\Money;

$settings = settings()
    // set any settings you want
    ->setThousandsSeparator('.')
    ->setDecimalSeparator(',')
    ->setHasSpaceBetween(false)
    ->setEndsWith0(true);

Money::set($settings);
```

---

📌 Back to the [contents](/docs/04_money/README.md).
