# API services

They are provided to get information about **exchange rates** online. 

There are two available options:
1. Get latest rates.
2. Get rates on a specific day.

`latest` and `historical` modes, respectively.

## Which are provided by default?
At the moment, the library supports and provides 3 different API services:
1. [`ExchangeRatesAPI`](https://exchangeratesapi.io/)
2. [`OpenExchangeRates`](https://openexchangerates.org/)
2. [`ExchangeRate`](https://exchangerate.host/) - default

You can choose whatever you like by changing it in the property `service` of the config file.

## What if there is no service I want?

In this case, you can add your own service, see [here](/docs/05_services/add.md) for full details.

---

📌 Back to the [contents](/README.md#table-of-contents).
