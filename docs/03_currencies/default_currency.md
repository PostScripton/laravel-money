# Default currency

In order to get the default currency from the config file or override this value from the code.

## Usage

```php
// config/money.php

return [
    'default_currency' => 'RUB',

    // ...
];
```

```php
use PostScripton\Money\Currency;

Currency::getDefault(); // RUB currency object

Currency::setDefault(currency('USD'));

Currency::getDefault(); // USD currency object
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
