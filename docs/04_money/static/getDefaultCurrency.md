# `Money::getDefaultCurrency()`

returns the default currency object from the config file.

## Usage

```php
// config/money.php

return [
    'default_currency' => 'RUB',

    // ...
];
```

```php
use PostScripton\Money\Money;

Money::getDefaultCurrency(); // RUB currency object
```

---

📌 Back to the [contents](/docs/04_money/README.md).
