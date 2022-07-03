# `clone()`

creates an absolutely identical instance of the object.

## Methods

### `clone()`
**Returns**: `Money` (new instance with the same settings)

## Usage

```php
$bobReward = money(1000);

$johnReward = $bobReward
    // Add for both John and Bob because John refers to Bob's object
    ->add(500)
    // John's reward is 1500 as long as Bob's one but John's reward is independent now
    ->clone()
    // Multiplies John's reward by 2 without affecting Bob's reward at all
    ->multiply(2);

$bobReward->getPureAmount();    // 1500
$johnReward->getPureAmount();   // 3000
```

The next example is wrong for this purpose:

```php
$bobReward = money(1000);

$johnReward = $bobReward
    // Add for both John and Bob because John refers to Bob's object
    ->add(500)
    // Multiplies both John and Bob because John refers to Bob's object
    ->multiply(2);

$bobReward->getPureAmount();    // 3000
$johnReward->getPureAmount();   // 3000
```

---

📌 Back to the [contents](/docs/04_money/README.md).
