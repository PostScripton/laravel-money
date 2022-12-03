# 📄 Cloning

This is an essential part. Each and every method of an object returns itself.
As everybody knows, all objects in PHP are referential, so they don't get copied when you return it within a method or assign it to a variable.

Thus, if you want to assign object to another variable or something, you should clone it and work with an absolutely new instance that has identical configuration (amount, currency, settings).
To accomplish that, we make up with a convenient method, which is called `clone()`. It simply returns a new instance of a monetary object.

👀 See [here](/docs/04_money/object/clone.md) for full details.
