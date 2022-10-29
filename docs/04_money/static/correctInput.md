# `Money::correctInput()`
corrects an `<input type="number" />`'s value to the correct one.

## Usage

```php
use PostScripton\Money\Money;

// A number of digits after decimal: 2

$inputValue = "1234.567890";        // value that comes from <input> tag
Money::correctInput($inputValue);   // "1234.56"
```
It simply adjusts a number string to the expected number string with default settings applied.

---

📌 Back to the [contents](/docs/04_money/README.md).
