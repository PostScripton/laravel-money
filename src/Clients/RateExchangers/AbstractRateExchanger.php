<?php

namespace PostScripton\Money\Clients\RateExchangers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PostScripton\Money\Clients\AbstractClient;
use PostScripton\Money\Currency;
use PostScripton\Money\Exceptions\RateExchangerAPIChangedException;
use PostScripton\Money\Exceptions\RateExchangerException;
use Throwable;

abstract class AbstractRateExchanger extends AbstractClient implements RateExchanger
{
    /**
     * Get URI to endpoint in order to retrieve a real-time exchange rate <p>
     * The date argument is passed for a historical mode </p>
     * @param Carbon|null $date
     * @return string
     */
    abstract protected function getRateRequestPath(?Carbon $date = null): string;

    /**
     * Get options on getting exchange rate for Guzzle request
     * @param string $from
     * @param string|array $to
     * @return array
     */
    abstract protected function getRateRequestOptions(string $from, string|array $to): array;

    /**
     * Get exchange rate(s) from a response <p>
     * If it's an array of codes, then it should return as [code => rate] array </p><p>
     * If it's a single code, then just exchange rate should be returned </p>
     * @param array $response
     * @param string|array $to
     * @return float|array
     */
    abstract protected function getRateFromResponse(array $response, string|array $to): float|array;

    /**
     * Get URI to endpoint in order to retrieve all supported currencies by API provider
     * @return string
     */
    abstract protected function getSupportsRequestPath(): string;

    /**
     * Get supported currency codes from a response
     * @param array $response
     * @return array
     */
    abstract protected function getSupportedCodesFromResponse(array $response): array;

    /**
     * Check whether a response contains an error
     * @param array $response
     * @return bool
     */
    abstract protected function isErrorInResponse(array $response): bool;

    public function rate(Currency|string $from, Currency|string|array $to, ?Carbon $date = null): float|array
    {
        $preparedFrom = Currency::get($from)->getCode();
        $preparedTo = collect(is_array($to) ? $to : [$to])
            ->map(fn(Currency|string $code) => Currency::get($code)->getCode())
            ->when(
                is_array($to),
                fn(Collection $collection) => $collection->toArray(),
                fn(Collection $collection) => $collection->first(),
            );

        $path = $this->getRateRequestPath($date);
        $options = $this->getRateRequestOptions($preparedFrom, $preparedTo);

        $response = $this->getRequest($path, $options);

        $this->handleErrors($response);

        try {
            return $this->getRateFromResponse($response, $preparedTo);
        } catch (Throwable $exception) {
            throw new RateExchangerAPIChangedException(json_encode(func_get_args()), $exception);
        }
    }

    public function supports(array $codes): array
    {
        $response = $this->getRequest($this->getSupportsRequestPath());

        $this->handleErrors($response);

        $codes = collect($codes)
            ->map(fn(Currency|string $code) => Currency::get($code)->getCode())
            ->toArray();

        try {
            $supportedCodes = $this->getSupportedCodesFromResponse($response);

            return array_values(array_diff($codes, $supportedCodes));
        } catch (Throwable $exception) {
            throw new RateExchangerAPIChangedException(json_encode($codes), $exception);
        }
    }

    private function handleErrors(array $response): void
    {
        if ($this->isErrorInResponse($response)) {
            throw new RateExchangerException(sprintf(
                'Response error: %s',
                json_encode($response['result']),
            ));
        }
    }
}
