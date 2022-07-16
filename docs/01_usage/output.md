# 🖨️ Output
After creating and manipulating with the Money object, you'd like to print the money out to somewhere.

You can use one of the following ways:
```php
$money = money('12345000');

// Use toString()
$string = $money->toString();           // "$ 1 234.5"

// Explicitly assign object to string
$string = "Your balance is {$money}";   // "Your balance is $ 1 234.5"
```

In Blade:
```html
<p>Balance: {{ $money }}</p>
```

---

📌 Back to the [contents](/README.md#table-of-contents).
