# 💵 Money for Laravel PHP
[![GitHub release (latest by date)](https://img.shields.io/github/v/release/PostScripton/laravel-money)](https://packagist.org/packages/postscripton/laravel-money)
[![Total Downloads](https://img.shields.io/packagist/dt/postscripton/laravel-money.svg)](https://packagist.org/packages/postscripton/laravel-money)
[![License](https://img.shields.io/packagist/l/postscripton/laravel-money)](https://packagist.org/packages/postscripton/laravel-money)

## Introduction

Laravel-money is an open source library that simplifies life to convert numbers from a database (`'balance': 12340`) into money objects.
With all being said, you can calculate money, output it as a string, convert it between currencies online via API services as well as offline and more!

## Requirements
- PHP >= 7.4
- `guzzlehttp/guzzle` >= 7.0

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

## Table of Contents

1. ✅ Usage
    - [🧰 Creating](/docs/01_Usage/creating.md)
    - [🖨️ Output](/docs/01_Usage/output.md)
2. [⚙ Settings](/docs/02_Settings/base.md)
    - [Decimals](/docs/02_Settings/decimals.md)
    - [Thousands separator](/docs/02_Settings/thousands_separator.md)
    - [Decimal separator](/docs/02_Settings/decimal_separator.md)
    - [Ends with zero](/docs/02_Settings/ends_with_zero.md)
    - [Space between currency and amount](/docs/02_Settings/space_between.md)
    - [Currency](/docs/02_Settings/currency.md)
    - [Origin amount](/docs/02_Settings/origin.md)
3. [💲 Currencies](/docs/03_Currencies/base.md)
    - [Information](/docs/03_Currencies/information.md)
    - [Position](/docs/03_Currencies/position.md)
    - [Display](/docs/03_Currencies/display.md)
    - [Currency List](/docs/03_Currencies/currency_list.md)
4. [💵 Money](/docs/04_Money/base.md)
    - Static methods
        - [Getters](/docs/04_Money/static/getters.md)
        - [`Money::set()`](/docs/04_Money/static/set.md)
        - [`Money::make()`](/docs/04_Money/static/make.md)
        - [`Money::correctInput()`](/docs/04_Money/static/correctInput.md)
        - [`Money::parse()` (will be reworked)](/docs/04_Money/static/parse.md)
    - Object methods
        - Getters
            - [`getAmount()`](/docs/04_Money/object/getAmount.md)
            - [`getPureAmount()`](/docs/04_Money/object/getPureAmount.md)
            - [`getCurrency()`](/docs/04_Money/object/getCurrency.md)
        - Calculations
            - [`add()`](/docs/04_Money/object/add.md)
            - [`subtract()`](/docs/04_Money/object/subtract.md)
            - [`multiple()`](/docs/04_Money/object/multiple.md)
            - [`divide()`](/docs/04_Money/object/divide.md)
            - [`rebase()`](/docs/04_Money/object/rebase.md)
        - Object manipulations
            - [`clear()`](/docs/04_Money/object/clear.md)
            - [`clone()` (soon)](#)
        - Logical operations
            - [`isSameCurrency()`](/docs/04_Money/object/isSameCurrency.md)
            - [`isNegative()`](/docs/04_Money/object/isNegative.md)
            - [`isPositive()`](/docs/04_Money/object/isPositive.md)
            - [`isEmpty()`](/docs/04_Money/object/isEmpty.md)
            - [`lessThan()`](/docs/04_Money/object/lessThan.md)
            - [`lessThanOrEqual()`](/docs/04_Money/object/lessThanOrEqual.md)
            - [`greaterThan()`](/docs/04_Money/object/greaterThan.md)
            - [`greaterThanOrEqual()`](/docs/04_Money/object/greaterThanOrEqual.md)
            - [`equals()`](/docs/04_Money/object/equals.md)
        - Other
            - [`bind()`](/docs/04_Money/object/bind.md)
            - [`service()`](/docs/04_Money/object/service.md)
            - [`convertInto()`](/docs/04_Money/object/convertInto.md)
            - [`upload()`](/docs/04_Money/object/upload.md)
            - [`toString()`](/docs/04_Money/object/toString.md)
5. [API services](/docs/05_Services/base.md)
    - [Add your own](/docs/05_Services/add.md)
    
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