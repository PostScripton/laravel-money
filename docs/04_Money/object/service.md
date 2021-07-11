# `service()`

allows you to get access to the selected service from the config file.

See [here](/docs/05_Services/base.md) for more details.

## Methods

### `service()`
**Returns**: `ServiceInterface`

## Usage

```php
use Carbon\Carbon;

$money = money(1000);

$money->service(); // your chosen API service
$money->service()->rate('USD', 'RUB', Carbon::createFromDate(2010, 4, 27));
```

---

📌 Back to the [contents](/README.md#table-of-contents).