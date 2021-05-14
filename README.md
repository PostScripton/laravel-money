# 💵 Money for Laravel PHP
[![Latest Stable Version](https://img.shields.io/packagist/v/postscripton/laravel-money.svg)](https://packagist.org/packages/postscripton/laravel-money)
[![Total Downloads](https://img.shields.io/packagist/dt/postscripton/laravel-money.svg)](https://packagist.org/packages/postscripton/laravel-money)
[![License](https://img.shields.io/packagist/l/postscripton/laravel-money)](https://packagist.org/packages/postscripton/laravel-money)

This package provides a convenient way to convert numbers from a database like (`'balance': 123450`) into money strings for humans.

## Requirements
+ PHP 7.4+

## Installation
### via composer
```console
composer require postscripton/laravel-money 
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

### 🧰 Creating
In order to use this package, you need to create a Money object:

```php
use PostScripton\Money\Money;

$money = new Money(1234);
$money = Money::make(1234);
```

You can add following parameters for both object and static variants:
```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$money = new Money(1234, Currency::code('RUB'));
$money = new Money(1234, new MoneySettings);
$money = new Money(1234, Currency::code('RUB'), new MoneySettings);

// NOT: new Money(1234, new MoneySettings, Currency::code('RUB'))
```

### 🖨️ Output
After creating and manipulating with the Money object, you'd like to print the money out to somewhere.

You can use one of the following ways:
```php
use PostScripton\Money\Money;

$money = new Money(1234);

// Use toString()
$string = $money->toString();           // "$ 123.4"

// Explicitly assign object to string
$string = "Your balance is {$money}";   // "Your balance is $ 123.4"
```
In Blade:
```html
<p>Balance: {{ $money }}</p>
```

### ⚙️ Settings
If you want to customize settings for your Money object, you need to specify settings for it.

To set setting:
```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

// Method #1
$money = new Money(1234, new MoneySettings);

// Method #2
$money = new Money(1234);
$settings = new MoneySettings;
$money->bind($settings);

// Method #3
$money = new Money(1234);
$settings = new MoneySettings;
$settings->bind($money);

// Method #4
$money = new Money(1234); // Every Money object has settings by default even if it is not provided
```

To get settings:
```php
use PostScripton\Money\Money;

$money = new Money(1234);
$money->settings();
```

❗ **NOTE**
All the settings that are not provided or not changed will have default values, which were configured in the config file.

---

Following settings are provided:

#### Decimals
You may get or specify number of decimals `123.(4)`:

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(1234, $settings);

$money->settings()->getDecimals();    // 1
$money->toString();                 // "$ 123.4"

$money->settings()->setDecimals(2); 

$money->settings()->getDecimals();    // 2
$money->toString();                 // "$ 12.34" 
```

---

#### Thousands separator
You may get or specify a separator between thousands `1( )000( )000`:

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(10000000, $settings);

$money->settings()->getThousandsSeparator();  // " "
$money->toString();                         // "$ 1 000 000"

$money->settings()->setThousandsSeparator("'"); 

$money->settings()->getThousandsSeparator();  // "'"
$money->toString();                         // "$ 1'000'000"
```

---

#### Decimal separator
You may get or specify a separator for decimals `123(.)4`:

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(1234, $settings);

$money->settings()->getDecimalSeparator();    // "."
$money->toString();                         // "$ 123.4"

$money->settings()->setDecimalSeparator(","); 

$money->settings()->getDecimalSeparator();    // ","
$money->toString();                         // "$ 123,4"
```

---

#### Ends with Zero
You may get or specify whether money ends with 0 or not `100(.0)`:

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(1000, $settings);

$money->settings()->endsWith0();  // false
$money->toString();             // "$ 100"

$money->settings()->setEndsWith0(true); 

$money->settings()->endsWith0();  // true
$money->toString();             // "$ 100.0"
```

---

#### Space between currency and number
You may get or specify whether there is a space between currency and number `$( )123.4`:

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(1234, $settings);

$money->settings()->hasSpaceBetween();    // true
$money->toString();                     // "$ 100"

$money->settings()->setHasSpaceBetween(false);

$money->settings()->hasSpaceBetween();    // false
$money->toString();                     // "$100"
```

---

#### Currency
You may get or specify currency for money `($) 123.4`:

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(1234, $settings);

$money->settings()->getCurrency();                // PostScripton\Money\Currency class
$money->settings()->getCurrency()->getSymbol();   // "$"
$money->toString();                             // "$ 123.4"

$money->settings()->setCurrency(Currency::code('RUB'));

$money->settings()->getCurrency();                // PostScripton\Money\Currency class
$money->settings()->getCurrency()->getSymbol();   // "₽"
$money->toString();                             // "123.4 ₽"
```

There is a shortcut for getting a currency:
```php
use PostScripton\Money\Money;

$money = new Money(1234);
$money->getCurrency(); // the same as: $money->settings()->getCurrency()
```

---

#### Origin number
You may get or specify origin number whether it is integer or float.
Means in which way numbers are stored in a database:

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(1234, $settings);

$money->settings()->getOrigin();  // 0 (MoneySettings::ORIGIN_INT)
$money->toString();             // "$ 123.4"
```

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = new MoneySettings;
$money = new Money(1234.567, $settings);

$money->settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

$money->settings()->getOrigin();  // 1 (MoneySettings::ORIGIN_FLOAT)
$money->toString();             // "$ 1234.6"
```

❗ If origin is set as **integer**, then the number divides on computed value to get a number with right amount of decimals.

❗ If origin is set as **float**, then the numbers leaves the same but removes unnecessary decimal digits.

---

### 💲 Currencies
Along with Money, as you have already noticed, Currencies are also provided. In many methods you have to pass a Currency object.

In order to get a specific currency:

```php
use PostScripton\Money\Currency;

$usd = Currency::code('USD');
$usd = Currency::code('usd');
$usd = Currency::code('840');
```

❗ Only international codes such as USD / 840, EUR / 978, RUB / 643 and so on should be used as a code.

---

You can also get or change some data from Currency object:

#### Information

```php
use PostScripton\Money\Currency;

$usd = Currency::code('USD');

$usd->getFullName();    // "United States dollar"
$usd->getName();        // "dollar"
$usd->getCode();        // "USD"
$usd->getNumCode();     // "840"
$usd->getSymbol();      // "$"
$usd->getPosition();    // 0 (Currency::POS_START)
$usd->getDisplay();     // 10 (Currency::DISPLAY_SYMBOL)
```

`getSymbol()` takes an index as first parameter only if there are more than one symbol for the currency.

```php
use PostScripton\Money\Currency;

Currency::setCurrencyList(Currency::LIST_ALL);

$currency = Currency::code('EGP');

// ['£', 'ج.م']
$currency->getSymbol();     // '£'
$currency->getSymbol(1);    // 'ج.م'
```

---

#### Position
You may specify the position of the currency on display.
Use following constants:

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

$money = new Money(1234);

$money->settings()->getCurrency()->getPosition(); // 0 (Currency::POS_START)
$money->toString();                             // "$ 123.4"

$money->settings()->getCurrency()->setPosition(Currency::POS_END);

$money->settings()->getCurrency()->getPosition(); // 1 (Currency::POS_END)
$money->toString();                             // "123.4 $"
```

---

#### Display
You may specify the way to display the currency whether it will be as an iso-code or a symbol.
Use following constants:

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

$money = new Money(1234);

$money->settings()->getCurrency()->getDisplay();  // 10 (Currency::DISPLAY_SYMBOL)
$money->toString();                             // "$ 123.4"

$money->settings()->getCurrency()->setDisplay(Currency::DISPLAY_CODE);

$money->settings()->getCurrency()->getDisplay();  // 11 (Currency::DISPLAY_CODE)
$money->toString();                             // "USD 123.4"

// If you don't like the look of the code at the beginning
$money->settings()->getCurrency()->setPosition(Currency::POS_END);
$money->toString();                             // "123.4 USD"
```

---

#### Currency List
If you wish, you may select another currency list.
To select currency list by default, go to the `config/money.php` and find there `currency_list`.

All the lists are located at `vendor/postscripton/money/src/List`, so if you want, you can check something up there.

```php
use PostScripton\Money\Currency;

Currency::setCurrencyList(Currency::LIST_POPULAR);
Currency::code('USD');

Currency::setCurrencyList(Currency::LIST_ALL);
Currency::code('EGP');
```
Following constants are provided:
```php
use PostScripton\Money\Currency;

Currency::LIST_ALL;
Currency::LIST_POPULAR;
Currency::LIST_CONFIG; // returns back to list what you've written in the config
```

---

### 💵 Money
Here we are, prepared and ready to create our own Money objects.

There are separation into static methods and object ones.

Let's start with static:

---

#### Available static methods

##### `Money::make()`
creates a Money object

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

// Default currency: USD

Money::make(0);                               // "$ 0"
Money::make(1230);                            // "$ 123"
Money::make(1234);                            // "$ 123.4"
Money::make(12345);                           // "$ 1 234.5"

Money::make(12345, Currency::code('RUB'));    // "1 234.5 ₽"
```

Method `make()` is a synonym of an object's constructor.
All the ways to pass parameters have already been discussed at the beginning.

---

##### `Money::correctInput()`
corrects an `<input type="number" />`'s value to the correct one

```php
use PostScripton\Money\Money;

// Number of digits after decimal: 2

$input_value = "1234.567890";       // value that comes from <input> tag
Money::correctInput($input_value);  // "1234.56"
```
It simply adjusts a number string to the expected number string with default settings applied

---

#### Available object's methods

##### `getNumber()`
gives you the formatted number

```php
use PostScripton\Money\Money;

$money = new Money(12345);
$money->getNumber(); // "1 234.5"
```

---

##### `getPureNumber()`
gives you pure number for calculating

```php
use PostScripton\Money\Money;

$money = new Money(132.76686139139672);
$money->getPureNumber(); // 132.76686139139672
```

---

##### `add()`

adds a number to the money

```php
use PostScripton\Money\Money;

$money = new Money(1000);   // "$ 100"
$money->add(500);           // "$ 150"
```

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$money = new Money(1000);                       // "$ 100"
$money->add(50.0, MoneySettings::ORIGIN_FLOAT); // "$ 150"
```

```php
use PostScripton\Money\Money;
use PostScripton\Money\Currency;

$m1 = new Money(1000);                          // "$ 100"
$m2 = new Money(500);                           // "$ 50"
$m3 = new Money(500, Currency::code('RUB'));    // "50 ₽"

$m1->add($m2);                                  // "$ 150"
$m1->add($m3);                                  // MoneyHasDifferentCurrenciesException
```

---

##### `subtract()`

subtracts a number from the money

```php
use PostScripton\Money\Money;

$money = new Money(1500);   // "$ 150"
$money->subtract(500);      // "$ 100"
```

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$money = new Money(1500);                               // "$ 150"
$money->subtract(50.0, MoneySettings::ORIGIN_FLOAT);    // "$ 100"
```

```php
use PostScripton\Money\Money;
use PostScripton\Money\Currency;

$m1 = new Money(1500);                          // "$ 150"
$m2 = new Money(500);                           // "$ 50"
$m3 = new Money(500, Currency::code('RUB'));    // "50 ₽"

$m1->subtract($m2);                             // "$ 100"
$m1->subtract($m3);                             // MoneyHasDifferentCurrenciesException
```

---

##### `rebase()`

a number to which the money will be rebased

```php
use PostScripton\Money\Money;

$money = new Money(1500);   // "$ 150"
$money->rebase(100);        // "$ 10"
```

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$money = new Money(1500);                           // "$ 150"
$money->rebase(10.0, MoneySettings::ORIGIN_FLOAT);  // "$ 10"
```

```php
use PostScripton\Money\Money;
use PostScripton\Money\Currency;

$m1 = new Money(1000);                          // "$ 100"
$m2 = new Money(750);                           // "$ 75"
$m3 = new Money(750, Currency::code('RUB'));    // "75 ₽"

$m1->rebase($m2);                               // "$ 75"
$m1->rebase($m3);                               // MoneyHasDifferentCurrenciesException
```

---

##### `convertOfflineInto()`

converts Money object into the chosen currency

```php
use PostScripton\Money\Currency;
use PostScripton\Money\Money;

$coeff = 75.32;
$rub = new Money(10000, Currency::code('RUB'));

$usd = $rub->convertOfflineInto(Currency::code('USD'), 1 / $coeff);
$usd->getPureNumber(); // 132.76686139139672
// gives you the same object, not cloned but changed
```

---

##### `upload()`
casts the money to the origin number way according to origin settings whether it is an integer or float.
It helps you to save the money back into your database because it gives you a certain number according to the way your database store money data.

You can set the origin for all the money objects.

```php
use PostScripton\Money\Money;
use PostScripton\Money\MoneySettings;

$settings = (new MoneySettings())
    ->setOrigin(MoneySettings::ORIGIN_FLOAT);

$int = new Money(1234.567890);
$float = new Money(1234.567890, $settings);

$int->upload();       // 12345
$float->upload();     // 1234.5
```

---

##### `toString()`
represents Money object as a string. The ways to convert into a string have already been mentioned at the beginning

```php
use PostScripton\Money\Money;

$money = new Money(1234);
$money->toString(); // "$ 123.4"
```
