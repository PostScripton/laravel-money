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

1. [Information](/docs/03_Currencies/information.md)
2. [Position](/docs/03_Currencies/position.md)
3. [Display](/docs/03_Currencies/display.md)
4. [Currency List](/docs/03_Currencies/currency_list.md)

---

📌 Back to the [contents](/README.md#table-of-contents).