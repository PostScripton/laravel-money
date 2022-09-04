# Position
You may specify the position of the currency on display.

## Methods

### `getPosition()`
**Returns**: `CurrencyPosition`.

### `setPosition([CurrencyPosition $position = CurrencyPosition::Start])`
**Parameters**:
1. `[CurrencyPosition $position = CurrencyPosition::Start]` (*optional*).

**Returns**: `Currency`

## Usage

```php
use PostScripton\Money\Enums\CurrencyPosition;

$money = money('1234000');

$money->getCurrency()->getPosition();   // CurrencyPosition::Start
$money->toString();                     // "$ 123.4"

$money->getCurrency()->setPosition(CurrencyPosition::End);

$money->getCurrency()->getPosition();   // CurrencyPosition::End
$money->toString();                     // "123.4 $"
```

---

📌 Back to the [contents](/docs/03_currencies/README.md).
