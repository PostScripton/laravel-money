# 💵 Money for Laravel PHP
[![GitHub release (latest by date)](https://img.shields.io/github/v/release/PostScripton/laravel-money)](https://packagist.org/packages/postscripton/laravel-money)
[![Total Downloads](https://img.shields.io/packagist/dt/postscripton/laravel-money.svg)](https://packagist.org/packages/postscripton/laravel-money)
[![License](https://img.shields.io/github/license/PostScripton/laravel-money)](https://packagist.org/packages/postscripton/laravel-money)

## Introduction

Laravel-money is an open source library that simplifies life to convert numbers from a database (`'balance': 12340`) into money objects.
With all being said, you can calculate money, output it as a string, convert it between currencies online via API services as well as offline and more!

## Requirements
- PHP: `^7.4` or `^8.0`
- `guzzlehttp/guzzle`: `^7.0`

## Installation

### via composer
```bash
composer require postscripton/laravel-money 
```

### Publishing
Publish the config file through:
```bash
php artisan vendor:publish --provider=PostScription\Money\MoneyServiceProvider
```

or

```bash
php artisan vendor:publish --tag=money
```

After all, the config file at `config/money.php` should be modified for your own purposes.

## ⏰ Quick start

### Migrations

Decide in which origin you want to store your money whether it is integer or float.
Integer is preferred because of database performance, precision and so on. The database makes more effort to work with DECIMAL, FLOAT, and DOUBLE types, bear in mind they may lose precision as well.

> Using floating point numbers to represent monetary amounts is almost a crime © Robert Martin

More about origins you can read [here](/docs/04_money/object/upload.md).

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    
    // one of them:
    $table->integer('price')->default(0); // for integer origin
    $table->decimal('price')->default(0); // for float origin
    
    $table->timestamps();
});
```

### Models

Cast your model's field to Money type within your Laravel application.

```php
// app/Models/Product.php

use Illuminate\Database\Eloquent\Model;
use PostScripton\Money\Casts\MoneyCast;

class Product extends Model
{
    // ...
    
    protected $casts = [
        // other casts
        
        'price' => MoneyCast::class,
    ];
}
```

👀 See [here](/docs/01_usage/casting.md) for full details.

### How to create and output?

```php
$money = money(1000); // $ 100

$newMoney = $money->clone()                 // clone it to work with independent object
    ->add(500, MoneySettings::ORIGIN_INT)   // $ 150
    ->subtract(money(600))                  // $ 90
    ->divide(1.5);                          // $ 60
$diff = $money->difference($newMoney);      // new Money instance, $40 ($100 - $60)

$money->toString();             // "$ 100"
"Your balance is {$newMoney}";  // "Your balance is $ 60"
"The difference is " . $diff;   // "The difference is $ 40"
```

👀 See [here](/docs/04_money/README.md) for full details.

### Converting currencies

```php
$usd = money(1000); // $ 100

$date = Carbon::parse('2000-12-31');

$offline = $usd->convertInto(currency('RUB'), 75.79);                   // 7 579 ₽
$online = $usd->convertInto(currency('RUB'));                           // 7 139.5 ₽ (today is 2021-10-14)
$onlineHistorical = $usd->convertInto(currency('RUB'), null, $date);    // ~2 816 ₽
```

👀 See [here](/docs/05_services/README.md) for full details.

## Table of Contents

1. ✅ Usage
    - [🧰 Creating](/docs/01_usage/creating.md)
    - [🖨️ Output](/docs/01_usage/output.md)
    - [📄 Cloning](/docs/01_usage/cloning.md)
    - [🎯 Casting](/docs/01_usage/casting.md)
2. [⚙ Settings](/docs/02_settings/README.md)
    - [Decimals](/docs/02_settings/decimals.md)
    - [Thousands separator](/docs/02_settings/thousands_separator.md)
    - [Decimal separator](/docs/02_settings/decimal_separator.md)
    - [Ends with zero](/docs/02_settings/ends_with_zero.md)
    - [Space between currency and amount](/docs/02_settings/space_between.md)
    - [Currency](/docs/02_settings/currency.md)
    - [Origin amount](/docs/02_settings/origin.md)
3. [💲 Currencies](/docs/03_currencies/README.md)
    - [Information](/docs/03_currencies/information.md)
    - [Position](/docs/03_currencies/position.md)
    - [Display](/docs/03_currencies/display.md)
    - [Currency List](/docs/03_currencies/currency_list.md)
    - [Preferred symbol](/docs/03_currencies/preferred_symbol.md)
    - [Get current currencies](/docs/03_currencies/get_currencies.md)
4. [💵 Money](/docs/04_money/README.md)
    - Static methods
        - [Getters](/docs/04_money/static/getters.md)
        - [`Money::set()`](/docs/04_money/static/set.md)
        - [`Money::make()`](/docs/04_money/static/make.md)
        - [`Money::correctInput()`](/docs/04_money/static/correctInput.md)
        - [`Money::parse()`](/docs/04_money/static/parse.md)
    - Object methods
        - Getters
            - [`getAmount()`](/docs/04_money/object/getAmount.md)
            - [`getPureAmount()`](/docs/04_money/object/getPureAmount.md)
            - [`getCurrency()`](/docs/04_money/object/getCurrency.md)
        - Calculations
            - [`add()`](/docs/04_money/object/add.md)
            - [`subtract()`](/docs/04_money/object/subtract.md)
            - [`multiple()`](/docs/04_money/object/multiple.md)
            - [`divide()`](/docs/04_money/object/divide.md)
            - [`rebase()`](/docs/04_money/object/rebase.md)
        - Object manipulations
            - [`floor()`](/docs/04_money/object/floor.md)
            - [`ceil()`](/docs/04_money/object/ceil.md)
            - [`clone()`](/docs/04_money/object/clone.md)
        - Logical operations
            - [`isSameCurrency()`](/docs/04_money/object/isSameCurrency.md)
            - [`isNegative()`](/docs/04_money/object/isNegative.md)
            - [`isPositive()`](/docs/04_money/object/isPositive.md)
            - [`isEmpty()`](/docs/04_money/object/isEmpty.md)
            - [`lessThan()`](/docs/04_money/object/lessThan.md)
            - [`lessThanOrEqual()`](/docs/04_money/object/lessThanOrEqual.md)
            - [`greaterThan()`](/docs/04_money/object/greaterThan.md)
            - [`greaterThanOrEqual()`](/docs/04_money/object/greaterThanOrEqual.md)
            - [`equals()`](/docs/04_money/object/equals.md)
        - Other
            - [`bind()`](/docs/04_money/object/bind.md)
            - [`service()`](/docs/04_money/object/service.md)
            - [`convertInto()`](/docs/04_money/object/convertInto.md)
            - [`upload()`](/docs/04_money/object/upload.md)
            - [`toString()`](/docs/04_money/object/toString.md)
5. [API services](/docs/05_services/README.md)
    - [Add your own](/docs/05_services/add.md)

## Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/PostScripton/laravel-money/).

### Pull Requests

- **[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - The easiest way to apply the conventions is to use [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) just by running:
    - ```bash
      composer app:check-build
      ```
    - ```bash
      composer app:cs-fix
      ```
- **Add tests!** - Your patch won't be accepted if it doesn't have tests.
- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- Create feature branches - Don't ask us to pull from your master branch.
- **One pull request per a feature** - If you want to do more than one thing, send multiple pull requests. Features must be atomic, that is, do not contain unnecessary things.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

### Security

If you discover any security-related issues, please email [postscripton.sp@gmail.com](mailto:postscripton.sp@gmail.com) instead of using the issue tracker.

### Wishes

We would be really pleased if you considered helping us with:
1. **A brand-new name of the library in one word**. At the same time it has to make sense, be meaningful.
   For example, [Carbon](https://carbon.nesbot.com/) sounds quite good.
2. **Logo**. The README would look much better with an attractive banner-logo at the top.

---

**Happy coding!** 😄🎉⌨️

## License

Laravel-money is an open-source library under the [MIT license](/LICENSE.txt). 
