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
    <img src="https://img.shields.io/github/actions/workflow/status/PostScripton/laravel-money/ci.yml?branch=4.x&logo=github&style=for-the-badge" alt="GitHub Workflow Status (branch)"> 
</a>
<a href="https://codecov.io/gh/PostScripton/laravel-money" target="_blank"> 
    <img src="https://img.shields.io/codecov/c/gh/PostScripton/laravel-money/4.x?token=V1ACJR1NM5&logo=codecov&style=for-the-badge" alt="Coverage percent"/> 
</a>
</div>

## Introduction

💵 **Laravel Money** is an open source package that provides you a convinient way to work with numbers from database with high precision and use them as monetary objects.
With this package, you can easily operate, compare, format, and even convert monetary objects to other currencies using external API providers.

## Upgrade guide

- [`3.x` to `4.x`](/docs/upgrade/3.x_to_4.x.md)

## Requirements

- PHP: `^8.1`
- `guzzlehttp/guzzle`: `^7.5`
- `bcmath` extension

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
3. [🎨️ Formatting](/docs/02_formatting/README.md)
4. [💲 Currencies](/docs/03_currencies/README.md)
    - [Default currency](/docs/03_currencies/default_currency.md)
    - [Information](/docs/03_currencies/information.md)
    - [Position](/docs/03_currencies/position.md)
    - [Display](/docs/03_currencies/display.md)
    - [Preferred symbol](/docs/03_currencies/preferred_symbol.md)
    - [Collection methods](/docs/03_currencies/collection_methods.md)
5. [💵 Money](/docs/04_money/README.md)
    - [💲 Currency](/docs/04_money/currency.md)
    - Static methods
        - [`Money::setFormatter()`](/docs/04_money/static/setFormatter.md)
        - [`Money::of()`](/docs/04_money/static/of.md)
        - [`Money::zero()`](/docs/04_money/static/zero.md)
        - [`Money::parse()`](/docs/04_money/static/parse.md)
        - [`Money::min()`](/docs/04_money/static/min.md)
        - [`Money::max()`](/docs/04_money/static/max.md)
        - [`Money::avg()`](/docs/04_money/static/avg.md)
        - [`Money::sum()`](/docs/04_money/static/sum.md)
    - Object methods
        - Getters
            - [`getAmount()`](/docs/04_money/object/getAmount.md)
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
            - [`negate()`](/docs/04_money/object/negate.md)
            - [`clone()`](/docs/04_money/object/clone.md)
        - Logical operations
            - [Comparing currencies](/docs/04_money/object/comparing_currencies.md)
            - [`isNegative()`](/docs/04_money/object/isNegative.md)
            - [`isPositive()`](/docs/04_money/object/isPositive.md)
            - [`isZero()`](/docs/04_money/object/isZero.md)
            - [`lessThan()`](/docs/04_money/object/lessThan.md)
            - [`lessThanOrEqual()`](/docs/04_money/object/lessThanOrEqual.md)
            - [`greaterThan()`](/docs/04_money/object/greaterThan.md)
            - [`greaterThanOrEqual()`](/docs/04_money/object/greaterThanOrEqual.md)
            - [`equals()`](/docs/04_money/object/equals.md)
        - [Converting to string](/docs/02_formatting/README.md#other-to-string-methods)
        - [Converting currencies](/docs/04_money/object/converting_currencies.md)
6. [API rate exchangers](/docs/05_rate_exchangers/README.md)
    - [Add your own](/docs/05_rate_exchangers/add.md)

## License

Laravel-money is an open-source library under the [MIT license](/LICENSE.txt). 
