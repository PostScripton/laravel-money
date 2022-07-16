# Position
You may specify the position of the currency on display.

## Constants

1. `Currency::POSITION_START` (moves a currency to the start)
2. `Currency::POSITION_END` (moves a currency to the end)

## Methods

### `getPosition()`
**Returns**: `int` - one of the constants.

### `setPosition([int $position = Currency::POSITION_START])`
**Parameters**:
1. `[int $position = Currency::POSITION_START]` (*optional*) - one of the constants.

**Returns**: `void`

## Usage

```php
use PostScripton\Money\Currency;

$money = money('1234000');

$money->settings()->getCurrency()->getPosition();   // 0 (Currency::POSITION_START)
$money->toString();                                 // "$ 123.4"

$money->settings()->getCurrency()->setPosition(Currency::POSITION_END);

$money->settings()->getCurrency()->getPosition();   // 1 (Currency::POSITION_END)
$money->toString();                                 // "123.4 $"
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
