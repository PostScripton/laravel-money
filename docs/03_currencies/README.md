# 💲 Currencies

Along with Money, as you have already noticed, Currencies are also provided. In many methods you have to pass a Currency object.

In order to get a specific currency:

```php
use PostScripton\Money\Currency;

$usd = Currency::code('USD');
$usd = Currency::code('usd');
$usd = Currency::code('840');

$usd = currency('USD');
$usd = currency('usd');
$usd = currency('840');
```

❗ Only international codes such as USD / 840, EUR / 978, RUB / 643 and so on should be used as a code.
(And your own currencies' codes 😉)

---

### Currency's data

You can also get or change some data from Currency object:

1. [Information](/docs/03_currencies/information.md)
2. [Position](/docs/03_currencies/position.md)
3. [Display](/docs/03_currencies/display.md)
4. [Currency List](/docs/03_currencies/currency_list.md)
5. [Preferred symbol](/docs/03_currencies/preferred_symbol.md)
6. [Get current currencies](/docs/03_currencies/get_currencies.md)

---

📌 Back to the [contents](/README.md#table-of-contents).
