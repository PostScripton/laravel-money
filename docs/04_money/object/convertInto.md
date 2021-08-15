# `convertInto()`

converts money into another currency using an exchange rate between currencies.

See [here](/docs/05_services/README.md) for full details.

For example:
```text
USD -> RUB = 75.79 / 1
RUB -> USD = 1 / 75.79
```

## Methods

### `convertInto(Currency $currency, [?float $rate = null], [?Carbon $date = null])`
**Parameters**:
1. `Currency $currency` - currency you want to convert into.
2. `[?float $rate = null]` - rate of the money's currency and the chosen one.
   - If `$rate` is not passed, then currencies will be converted online via API services.
3. `[?Carbon $date = null]` - historical mode. Pass the date you want to get rate of.
   - Works only if `$rate` is not passed because it works with API.
    
**Returns**: `Money` - new money instance.

## Exceptions

1. `ServiceDoesNotSupportCurrencyException` - is thrown when `$currency` argument is not supported by an API service.

## Usage

### Online converting

In order to convert currencies with real-time rate.

```php
$rub = money(10000, currency('RUB'));

$usd = $rub->convertInto(currency('USD'));
$back_rub = $usd->convertInto(currency('RUB'));

$rub->toString() === $back_rub->toString(); // true
```

#### Historical mode

To convert currencies according to the exact date.

```php
use Carbon\Carbon;

$rub = money(10000, currency('RUB'));

$usd = $rub->convertInto(currency('USD'));
$historical_usd = $rub->convertInto(currency('USD'), null, Carbon::createFromDate(2010, 4, 27)))

$usd->getPureAmount() === $historical_usd->getPureAmount(); // false
```

### Offline converting

For converting offline, you need to know the rate.

```php
$rate = 75.32;
$rub = money(10000, currency('RUB'));                       // "1 000 ₽"

$usd = $rub->convertInto(currency('USD'), 1 / $rate);       // "$ 13.3"
$back_rub = $usd->convertInto(currency('RUB'), $rate / 1);  // "1 000 ₽"

$rub->isSameCurrency($usd);                                 // false
$rub->isSameCurrency($back_rub);                            // true
$rub->getPureAmount() === $back_rub->getPureAmount();       // true
```

---

📌 Back to the [contents](/README.md#table-of-contents).
