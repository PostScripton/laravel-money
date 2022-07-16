# `service()`

allows you to get access to the selected service from the config file.

👀 See [here](/docs/05_services/README.md) for full details.

## Methods

### `service()`
**Returns**: `ServiceInterface`

## Usage

```php
use Carbon\Carbon;

$money = money('1000000');

$money->service(); // your chosen API service
$money->service()->rate('USD', 'RUB', Carbon::createFromDate(2010, 4, 27));
```

---

📌 Back to the [contents](/docs/04_money/README.md).
