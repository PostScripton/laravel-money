# Add your own service

In order to add your own service and make it work, you need:
1. [Create a class](#through-extending-the-abstract-class).
2. [Add your service to the config file](#add-your-service-to-the-config-file).

## Creating a service class

There are two ways to create a class:
1. You can extend `PostScripton\Money\Services\AbstractService` where everything is ready, and you need to override some properties and methods.
2. You can extend `PostScripton\Money\Services\ServiceInterface` and implement methods on your own.

The first way is more convenient and preferred. 

### Through extending the interface

You need to implement following methods:
1. `rate()` that returns an exchange rate between two currencies.
2. `supports()` checks whether API service supports this currency or not.
3. `getClassName()` easy implementation, that copy-paste the following:
    ```php
   public function getClassName(): string
   {
       return static::class;
   } 
   ```
4. `url()` returns a base url of all API requests.

Not that easy, huh? See what you should do using the abstract class.

### Through extending the abstract class

#### Properties

There are some properties that you may need to override:

```php
protected string $currencies = 'symbols';   // currencies to convert into
protected string $base = 'base';            // currency to convert from
protected string $result = 'rates';         // rates of currencies
```

They are need to define keys of data that comes back.

#### Methods

```php
// runs with creating the service
public function boot(): void
{
    parent::boot();
    // your code here
}
```

```php
// the base query for creating a client
protected function baseQuery(): array
{
    return [
        'access_key' => $this->config['key']
    ];
}
```

```php
// a domain name
protected function domain(): string
{
    return 'example.com';
}
```

```php
// a uri after the domain name
protected function uri(): string
{
    return 'api/v1';
}
```

```php
// if there is a restriction to change a base currency, then what is this base currency?
protected static function BASE_CURRENCY(): string
{
    return 'USD';
}
```

```php
// a result may come in different formats
// for example, "USDRUB" or just "RUB"
// supported: FROM_TO_FORMAT and TO_FORMAT
protected function resultFormat(): int
{
    return self::TO_FORMAT;
}
```

```php
// a part of the url that leads to the supported currencies data

// example.com/api/v1/symbols
protected function supportedUri(): string
{
    return 'symbols';
}
```

```php
// a part of the url that leads to the latest rates data

// example.com/api/v1/latest
protected function latestUri(): string
{
    return 'latest';
}
```

```php
// a part of the url that leads to the historical rates data

// example.com/api/v1/2010-12-31
protected function historicalUri(Carbon $date, array &$query): string
{
    return $date->format(self::DATE_FORMAT);
}

// example.com/api/v1/historical?date=2010-12-31
protected function historicalUri(Carbon $date, array &$query): string
{
    // IT IS IMPORTANT TO MERGE THE QUERY!!!
    // In order not to lost you previous parameters such as api key, currencies and so on
    $query = array_merge($query, [
        'date' => $date->format(self::DATE_FORMAT)
    ]);

    return 'historical';
}
```

```php
// a way to get data from supported currencies if it is different
protected function supportedData(array $data, string $index): array
{
    return $data[$index];
}
```

```php
// a way to get data from latest rates if it is different
protected function latestData(array $data, string $index): float
{
    return $data[$this->result][$index];
}
```

```php
// validates if there are any errors
protected function validateResponse(array $data): void
{
    // Verify the server response
    if (array_key_exists('error', $data)) {
        throw new ServiceRequestFailedException($this->getClassName(), $data['error']['code'], $data['error']['info']);
    }
}
```

That’s all! All you need to do is to override some of these methods, not all of them!

However, you are free to even change `rate()` and `supports()` methods 😄

## Add your service to the config file

Firstly, you have to find a property `services`.
Secondly, add a new array that will be your service.

There are some rules that you should follow, otherwise [exceptions](#exceptions) will be thrown:
1. Array must contain a key `class` that has a full name of your service class.
2. Your class must inherit `PostScripton\Money\Services\ServiceInterface`.

Other options in the array will be available in your class (if it extends `PostScripton\Money\Services\AbstractService`) via:
```php
$this->config['your_additional_property_here'];
```

## Exceptions

1. `ServiceException` - is thrown when:
   - `services.*.class` property in the config has a class name that doesn't exist.
   - `service` property doesn't exist.
   - `service.*.class` property doesn't exist.
   - `service.*.class` property class doesn't inherit the `PostScripton\Money\Services\ServiceInterface`.

---

📌 Back to the [contents](/docs/05_services/README.md).
