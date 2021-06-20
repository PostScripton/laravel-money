# Origin number
You may get or specify origin number whether it is an integer or float.
Means in which way numbers are stored in a database:

## Constants

1. `MoneySettings::ORIGIN_INT` (sets origin to integer)
2. `MoneySettings::ORIGIN_FLOAT` (sets origin to float)

## Methods

### `getOrigin()`
**Returns**: `int` - one of available constants.

### `setOrigin(int $origin)`
**Parameters**:
1. `int $origin` - one of available constants.

**Returns**: `void`

## Usage

```php
use PostScripton\Money\MoneySettings;

$money = money(1234);

$money->settings()->getOrigin();    // 0 (MoneySettings::ORIGIN_INT)
$money->toString();                 // "$ 123.4"

$money->settings()->setOrigin(MoneySettings::ORIGIN_FLOAT);

$money->settings()->getOrigin();    // 1 (MoneySettings::ORIGIN_FLOAT)
$money->toString();                 // "$ 1234.6"
```

❗ If origin is set as an **integer**, then the number divides on computed value to get a number with right amount of decimals.

❗ If origin is set as a **float**, then the numbers leaves the same but removes unnecessary decimal digits.

---

📌 Back to the [contents](/README.md#table-of-contents).