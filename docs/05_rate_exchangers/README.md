# API rate exchangers

They are provided to get information about **exchange rates** online. 

There are two available options:
1. Get latest rates.
2. Get rates for a specific day.

`latest` and `historical` modes, respectively.

## Which are provided by default?

At the moment, the package supports and provides 3 different API providers:
1. [`Fixer`](https://fixer.io/)
2. [`OpenExchangeRates`](https://openexchangerates.org/)
2. [`ExchangeRate`](https://exchangerate.host/) - default

You can choose whatever you like by changing it in the property `rate_exchanger` of the config file.

### What if there is no rate exchanger I want?

In this case, you can add your own, 👀 see [here](/docs/05_rate_exchangers/add.md) for full details.

## Access to RateExchanger instance

In order to get access to RateExchanger you can call the interface from [Service Container](https://laravel.com/docs/9.x/container#main-content) like this:

```php
use PostScripton\Money\Clients\RateExchangers\RateExchanger;

app(RateExchanger::class)->supports(['USD', 'RUB']);
app(RateExchanger::class)->rate('USD', 'RUB', now()->subYear());
```

Or you can use a cached variant because it implements the interface as well:

```php
use PostScripton\Money\Cache\RateExchangerCache;
use PostScripton\Money\Currencies;

app(RateExchangerCache::class)->supports(Currencies::getCodesArray());
app(RateExchangerCache::class)->rate('USD', 'RUB', now()->subYear());

app(RateExchangerCache::class)->clear(); // deletes all cached data
```

---

📌 Back to the [contents](/README.md#table-of-contents).
