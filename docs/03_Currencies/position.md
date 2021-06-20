# Position
You may specify the position of the currency on display.

## Constants

1. `Currency::POS_START` (moves a currency to the start)
2. `Currency::POS_END` (moves a currency to the end)

## Methods

### `getPosition()`
**Returns**: `int` - one of the constants.

### `setPosition([int $position = Currency::POS_START])`
**Parameters**:
1. `[int $position = Currency::POS_START]` (*optional*) - one of the constants.

**Returns**: `void`

## Usage

```php
use PostScripton\Money\Currency;

$money = money(1234);

$money->settings()->getCurrency()->getPosition();   // 0 (Currency::POS_START)
$money->toString();                                 // "$ 123.4"

$money->settings()->getCurrency()->setPosition(Currency::POS_END);

$money->settings()->getCurrency()->getPosition();   // 1 (Currency::POS_END)
$money->toString();                                 // "123.4 $"
```

---

📌 Back to the [contents](/README.md#table-of-contents).