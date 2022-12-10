# 💵 Money
Here we are, prepared and ready to create our own monetary objects.

There are *static* methods as well as *object* ones.

1. [💲 Currency](/docs/04_money/currency.md)
2. Static methods
   - [`Money::getDefaultCurrency()`](/docs/04_money/static/getDefaultCurrency.md)
   - [`Money::setDefaultCurrency()`](/docs/04_money/static/setFormatter.md)
   - [`Money::setFormatter()`](/docs/04_money/static/setDefaultCurrency.md)
   - [`Money::of()`](/docs/04_money/static/of.md)
   - [`Money::parse()`](/docs/04_money/static/parse.md)
   - [`Money::correctInput()`](/docs/04_money/static/correctInput.md)
3. Object methods
    - Getters
        - [`getAmount()`](/docs/04_money/object/getAmount.md)
    - Calculations
        - [`add()`](/docs/04_money/object/add.md)
        - [`subtract()`](/docs/04_money/object/subtract.md)
        - [`multiply()`](/docs/04_money/object/multiply.md)
        - [`divide()`](/docs/04_money/object/divide.md)
        - [`rebase()`](/docs/04_money/object/rebase.md)
    - Object manipulations
        - [`floor()`](/docs/04_money/object/floor.md)
        - [`ceil()`](/docs/04_money/object/ceil.md)
        - [`absolute()`](/docs/04_money/object/absolute.md)
        - [`clone()`](/docs/04_money/object/clone.md)
    - Logical operations
        - [`isSameCurrency()`](/docs/04_money/object/isSameCurrency.md)
        - [`isNegative()`](/docs/04_money/object/isNegative.md)
        - [`isPositive()`](/docs/04_money/object/isPositive.md)
        - [`isZero()`](/docs/04_money/object/isZero.md)
        - [`lessThan()`](/docs/04_money/object/lessThan.md)
        - [`lessThanOrEqual()`](/docs/04_money/object/lessThanOrEqual.md)
        - [`greaterThan()`](/docs/04_money/object/greaterThan.md)
        - [`greaterThanOrEqual()`](/docs/04_money/object/greaterThanOrEqual.md)
        - [`equals()`](/docs/04_money/object/equals.md)
    - [Converting to string](/docs/02_formatting/README.md#other-to-string-methods)
    - Other `(deprecated)`
        - [`service()`](/docs/04_money/object/service.md)
        - [`convertInto()`](/docs/04_money/object/convertInto.md)

---

📌 Back to the [contents](/README.md#table-of-contents).
