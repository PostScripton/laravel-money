<div align="center">
    <img src="./.github/banner-logo.png" alt="Banner Logo"/>
</div>

<div align="center" style="margin-top: 1rem">
<a href="https://packagist.org/packages/postscripton/laravel-money" target="_blank">
    <img src="https://img.shields.io/github/v/release/PostScripton/laravel-money?style=for-the-badge" alt="Release version"/>
</a>
<a href="https://packagist.org/packages/postscripton/laravel-money" target="_blank">
    <img src="https://img.shields.io/packagist/dt/postscripton/laravel-money.svg?style=for-the-badge" alt="Total downloads"/>
</a>
<a href="https://packagist.org/packages/postscripton/laravel-money" target="_blank">
    <img src="https://img.shields.io/packagist/dm/postscripton/laravel-money?style=for-the-badge" alt="Downloads per month"/>
</a>
<a href="./LICENSE.txt" target="_blank">
    <img src="https://img.shields.io/github/license/PostScripton/laravel-money?style=for-the-badge" alt="License"/>
</a>
</div>

<div align="center" style="margin-top: 1rem">
<a href="https://github.com/PostScripton/laravel-money/actions/workflows/ci.yml?query=branch%3A4.x" target="_blank"> 
    <img src="https://img.shields.io/github/workflow/status/PostScripton/laravel-money/Continuous%20Integration/4.x?logo=github&style=for-the-badge" alt="GitHub Workflow Status (branch)"> 
</a>
<a href="https://codecov.io/gh/PostScripton/laravel-money" target="_blank"> 
    <img src="https://img.shields.io/codecov/c/gh/PostScripton/laravel-money/4.x?token=V1ACJR1NM5&logo=codecov&style=for-the-badge" alt="Coverage percent"/> 
</a>
</div>

## Introduction

**Laravel Money** is an open source library that simplifies life to convert numbers from a database (`'balance': 12340`) into money objects.
With all being said, you can calculate money, output it as a string, convert it between currencies online via API services as well as offline and more!

## Upgrade guide

- [`3.x` to `4.x`](/docs/upgrade/3.x_to_4.x.md)

## Requirements
- PHP: `^8.1`
- `guzzlehttp/guzzle`: `^7.0`

## Installation

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

1. [⏰ Quick start](/docs/quick_start.md)
2. ✅ Usage
    - [🧰 Creating](/docs/01_usage/creating.md)
    - [🖨️ Output](/docs/01_usage/output.md)
    - [📄 Cloning](/docs/01_usage/cloning.md)
    - [🎯 Casting](/docs/01_usage/casting.md)
    - [🚨 Validation rule](/docs/01_usage/validation_rule.md)
3. [⚙ Settings](/docs/02_settings/README.md)
    - [Decimals](/docs/02_settings/decimals.md)
    - [Thousands separator](/docs/02_settings/thousands_separator.md)
    - [Decimal separator](/docs/02_settings/decimal_separator.md)
    - [Ends with zero](/docs/02_settings/ends_with_zero.md)
    - [Space between currency and amount](/docs/02_settings/space_between.md)
4. [💲 Currencies](/docs/03_currencies/README.md)
    - [Information](/docs/03_currencies/information.md)
    - [Position](/docs/03_currencies/position.md)
    - [Display](/docs/03_currencies/display.md)
    - [Preferred symbol](/docs/03_currencies/preferred_symbol.md)
    - [Get current currencies](/docs/03_currencies/get_currencies.md)
5. [💵 Money](/docs/04_money/README.md)
    - [💲 Currency](/docs/04_money/currency.md)
    - Static methods
        - [Getters](/docs/04_money/static/getters.md)
        - [`Money::set()`](/docs/04_money/static/set.md)
        - [`Money::of()`](/docs/04_money/static/of.md)
        - [`Money::correctInput()`](/docs/04_money/static/correctInput.md)
        - [`Money::parse()`](/docs/04_money/static/parse.md)
    - Object methods
        - Getters
            - [`getAmount()`](/docs/04_money/object/getAmount.md)
            - [`getPureAmount()`](/docs/04_money/object/getPureAmount.md)
        - Calculations
            - [`add()`](/docs/04_money/object/add.md)
            - [`subtract()`](/docs/04_money/object/subtract.md)
            - [`multiply()`](/docs/04_money/object/multiply.md)
            - [`divide()`](/docs/04_money/object/divide.md)
            - [`rebase()`](/docs/04_money/object/rebase.md)
        - Object manipulations
            - [`floor()`](/docs/04_money/object/floor.md)
            - [`ceil()`](/docs/04_money/object/ceil.md)
            - [`absolute()`](/docs/04_money/object/absolute.md)
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
            - [`toString()`](/docs/04_money/object/toString.md)
6. [API services](/docs/05_services/README.md)
    - [Add your own](/docs/05_services/add.md)

## Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [GitHub](https://github.com/PostScripton/laravel-money/).

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

---

**Happy coding!** 😄🎉⌨️

## License

Laravel-money is an open-source library under the [MIT license](/LICENSE.txt). 
