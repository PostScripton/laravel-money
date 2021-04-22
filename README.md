# Money Formatter for PHP
[![Latest Stable Version](https://img.shields.io/packagist/v/postscripton/money.svg)](https://packagist.org/packages/postscripton/money)
[![Total Downloads](https://img.shields.io/packagist/dt/postscripton/money.svg)](https://packagist.org/packages/postscripton/money)
[![License](https://img.shields.io/packagist/l/postscripton/money)](https://packagist.org/packages/postscripton/money)

This package provides a convenient way to convert numbers from a database like (`'balance': 123450`) into money strings for humans.

## Requirements
+ PHP 7.1+

## Installation
### via composer
```console
composer require postscripton/money 
```
### Publishing
Publish the config file through:
```console
php artisan vendor:publish --provider=PostScription\Money\MoneyServiceProvider
```
or
```console
php artisan vendor:publish --tag=money
```

After all, the config file at `config/money.php` should be modified for your own purposes. 

## Usage

### Available methods

#### `Money::format()`
formats number from the database into money string

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

// Default currency: USD

Money::format(0);                               // "$ 0" or "$ 0.0"
Money::format(1230);                            // "$ 123"
Money::format(1234);                            // "$ 123.4"
Money::format(12345);                           // "$ 1 234.5"

Money::format(12345, Currency::code('RUB'));    // "1 234.5 ₽"
```

---

#### `Money::purify()`
clears the given money string to a simple clear number 

```php
use PostScripton\Money\Money;

$money = Money::format(12345);  // "$ 1 234.5"
Money::purify($money);          // "1234.5"
```

---

#### `Money::integer()`
converts the clear number into an integer so that the DB can easily store that number

```php
use PostScripton\Money\Money;

$money = Money::format(12345);      // "$ 1 234.5"
$number = Money::purify($money);    // "1234.5"
Money::integer((float)$number);     // 12345
```

---

#### `Money::convert()`
converts money from one currency to another

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

$coeff = 75.32;
$rub = Money::format(10000, Currency::code('RUB'));             // "1 000 ₽"

$usd = Money::convert($rub, Currency::code('USD'), 1 / $coeff);  // "$ 13.2"
$rub = Money::convert($usd, Currency::code('RUB'), $coeff);      // "994.2 ₽"
```
As you can see, in converting currencies there is a small calculation error, which has been caused by the small decimal digits

---

#### `Money::correctInput()`
corrects an `<input type="number" />`'s value to a normal string number

```php
use PostScripton\Money\Money;

// Number of digits after decimal: 2

$input_value = "1234.567890";       // value that comes from <input> tag
Money::correctInput($input_value);  // "1234.56"
```
It simply adjusts a number string to the expected number string with all the setting applied

---

#### `Currency::code()`
returns an instance of Currency

```php
use PostScripton\Money\Currency;

$usd = Currency::code('USD');

$usd->getCode();        // "USD"
$usd->getSymbol();      // "$"
$usd->getPosition();    // "start"
$usd->getCountries();   // [...] full names of countries where the currency is being used
```
