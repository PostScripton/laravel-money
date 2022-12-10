# Add your own provider

In order to add your own API provider and make it work, you need:
1. [Create a class](#through-extending-the-abstract-class).
2. [Add your provider to the config file](#add-your-provider-to-the-config-file).

## Creating a provider class

There are two ways to create a class:
1. You can extend `PostScripton\Money\Clients\RateExchangers\AbstractRateExchanger` where everything is ready, and you need to implement methods.
2. You can implement `PostScripton\Money\Clients\RateExchangers\RateExchanger` interface methods on your own.

The first way is more convenient and preferred. 

### Through implementing the interface

You need to implement following methods:
1. `rate()` that returns an exchange rate between two currencies.
2. `supports()` checks whether API service supports these currencies or not, and returns only UNSUPPORTED currencies.

### Through extending the abstract class

The documentation to these methods is in PHPDocs in the code.
You can also take a look at [already implemented rate exchangers](https://github.com/PostScripton/laravel-money/tree/4.x/src/Clients/RateExchangers) provided by this package because they extend the abstract class.

```php
abstract protected function getRateRequestPath(?Carbon $date = null): string;

abstract protected function getRateRequestOptions(string $from, string|array $to): array;

abstract protected function getRateFromResponse(array $response, string|array $to): float|array;

abstract protected function getSupportsRequestPath(): string;

abstract protected function getSupportedCodesFromResponse(array $response): array;

abstract protected function isErrorInResponse(array $response): bool;
```

That’s all! All you need to do is to implement these methods!

However, you are free to even change `rate()` and `supports()` methods 😄

## Add your provider to the config file

Firstly, you have to find a property `rate_exchanger`.
Secondly, add a new array that will represent your provider.

There are some rules that you should follow, otherwise [exceptions](#exceptions) will be thrown:
1. Array must contain a key `class` that has a full name of your API provider class.
2. Your class must inherit `PostScripton\Money\Clients\RateExchangers\RateExchanger`.

Other options in the array will be available in your class only if you'll specify a constructor:

```php
use GuzzleHttp\RequestOptions;

// ...

public function __constructor(protected readonly array $config)
{
    // this is an AbstractClient's constructor
    parent::__constructor([
        RequestOptions::QUERY => [
            'access_key' => $config['key']
        ],
    ]);
}
```

## Exceptions

1. `RateExchangerException` - is thrown when:
   - `rate_exchangers.*.class` property in the config has a class name that doesn't exist.
   - `rate_exchanger` doesn't exist in `rate_exchangers` property.
   - `rate_exchangers.*.class` property doesn't exist.
   - `rate_exchangers.*.class` property class doesn't inherit the `PostScripton\Money\Services\ServiceInterface`.

---

📌 Back to the [contents](/docs/05_rate_exchangers/README.md).
