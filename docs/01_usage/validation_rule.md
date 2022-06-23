# 🚨 Validation rule

You can easily validate incoming number in your FormRequest.

```php
use Illuminate\Foundation\Http\FormRequest;
use PostScripton\Money\Rules\Money as MoneyRule;

class PriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'min_price' => [
                'required',
                'money',
            ],
            'max_price' => [
                'required',
                MoneyRule::RULE_NAME,
            ],
            'default_price' => [
                'required',
                app(MoneyRule::class),
            ],
        ];
    }
}
```

---

📌 Back to the [contents](/README.md#table-of-contents).
