# 🔁💲 Converting currencies

The following methods allow to convert currencies from one to another.

👀 See [here](/docs/05_rate_exchangers/README.md) for full details about rate exchangers.

For example:
```text
USD -> RUB = 75.79 / 1
RUB -> USD = 1 / 75.79
```

⚠️ It is worth mentioning that converting forward and backward
with the same exchange rate may lead to different results because of losing precision.

## Methods

### `convertTo(Currency|string $currency, [?Carbon $date = null])`
**Parameters**:
1. `Currency|string $currency` - currency you want to convert to.
2. `[?Carbon $date = null]` (*optional*) - historical mode. Pass the date you want to get rate of.
    
**Returns**: `Money` - new money instance.

### `offlineConvertTo(Currency|string $currency, string $rate)`
**Parameters**:
1. `Currency|string $currency` - currency you want to convert to.
2. `string $rate` - a rate by which monetary object will be multiplied.
    
**Returns**: `Money` - new money instance.

## Exceptions

1. `CurrenciesNotSupportedByRateExchangerException` - is thrown when `$currency` argument is not supported by an API rate exchanger.
2. `RateExchangerException` - is thrown when `$date` argument is in the future. This also may be thrown because of API rate exchanger problems.

## Usage

### 🌐 Online converting

In order to convert currencies with real-time rate.

```php
$rub = money_parse('1000', 'RUB');

$usd = $rub->convertTo('USD');
$backRub = $usd->convertTo(currency('RUB'));

// may be true or false depending on API
// or delay between requests or loosing precision
$rub->equals($backRub);
```

#### 🏛️ Historical mode

To convert currencies according to the exact date.

```php
use Carbon\Carbon;

$rub = money_parse('1000', 'RUB');

$usd = $rub->convertTo('USD');
$historicalUsd = $rub->convertTo('USD', Carbon::createFromDate(2010, 4, 27));

$usd->equals($historicalUsd); // false
```

### 📵 Offline converting

For converting offline, you need to know the rate.

```php
$rate = 75.32;
$rub = money_parse('1000', 'RUB');

$usd = $rub->offlineConvertTo('USD', 1 / $rate);
$backRub = $usd->offlineConvertTo('RUB', $rate / 1);

$rub->isSameCurrency($usd);     // false
$rub->isSameCurrency($backRub); // true
$rub->equals($backRub);         // may be true or false because of loosing precision 
```

---

📌 Back to the [contents](/docs/04_money/README.md).
