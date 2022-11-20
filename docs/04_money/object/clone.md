# `clone()`

creates an absolutely identical instance of the object.

## Methods

### `clone()`
**Returns**: `Money` (new instance with the same settings)

## Usage

```php
$bobReward = money('1000000');

$johnReward = $bobReward
    // Add for both John and Bob because John refers to Bob's object
    ->add('500000')
    // John's reward is $150 as long as Bob's one but John's reward is independent now
    ->clone()
    // Multiplies John's reward by 2 without affecting Bob's reward at all
    ->multiply(2);

$bobReward->getAmount();    // "1500000"
$johnReward->getAmount();   // "3000000"
```

The next example is wrong for this purpose:

```php
$bobReward = money('1000000');

$johnReward = $bobReward
    // Add for both John and Bob because John refers to Bob's object
    ->add('500000')
    // Multiplies both John and Bob because John refers to Bob's object
    ->multiply(2);

$bobReward->getAmount();    // "3000000"
$johnReward->getAmount();   // "3000000"
```

---

📌 Back to the [contents](/docs/04_money/README.md).
