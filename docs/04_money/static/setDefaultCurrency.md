# `Money::setDefaultCurrency()`

sets a default currency that will be used for creating new monetary objects.

By default, is set by the value from the config file.

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

Money::setDefaultCurrency(currency('USD'));

Money::getDefaultCurrency(); // USD currency object
```

---

📌 Back to the [contents](/docs/04_money/README.md).
