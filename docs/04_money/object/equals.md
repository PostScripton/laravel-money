# `equals()`

checks whether two money objects are equal or not.

## Methods

### `equals(Money $money, [bool $strict = true])`
**Parameters**:
1. `Money $money`
2. `[bool $strict = true]` (*optional*) - whether it is `===` or `==`.

**Returns**: `bool`

## Usage

### Immutable way

```php
$johnReward = $bobReward = money('1000000');

// John has additional bonus $50
$winCoupon = money('500000');

$johnReward->add($winCoupon);

$bobReward->getPureAmount();        // "1500000"
$johnReward->getPureAmount();       // "1500000"
$johnReward->equals($bobReward);    // true
```

### Mutable way

```php
$johnReward = money('1000000');
$bobReward = money('1000000');

// John has additional bonus $50
$winCoupon = money('500000');

$johnReward = $johnReward->add($winCoupon);

$bobReward->getPureAmount();                        // "1000000"
$johnReward->getPureAmount();                       // "1500000"
$johnReward->equals($bobReward);                    // false
$johnReward->settings() === $bobReward->settings(); // false
```

---

📌 Back to the [contents](/docs/04_money/README.md).
