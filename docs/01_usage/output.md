# 🖨️ Output
After creating and manipulating with the Money object, you'd like to print the money out to somewhere.

You can use one of the following ways:
```php
$money = money(1234);

// Use toString()
$string = $money->toString();           // "$ 123.4"

// Explicitly assign object to string
$string = "Your balance is {$money}";   // "Your balance is $ 123.4"
```

In Blade:
```html
<p>Balance: {{ $money }}</p>
```

---

📌 Back to the [contents](/README.md#table-of-contents).