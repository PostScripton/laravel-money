<?php

namespace PostScripton\Money\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PostScripton\Money\MoneyServiceProvider;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractClient
{
    protected Client $client;

    abstract protected function baseUri(): string;

    public function __construct(array $config = [])
    {
        $this->client = new Client(array_merge_recursive([
            'base_uri' => $this->baseUri(),
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
                'Accepts' => 'application/json',
                'User-Agent' => MoneyServiceProvider::FULL_PACKAGE_NAME_WITH_VERSION,
            ],
        ], $config));
    }

    protected function getRequest(string $path, array $options = []): array
    {
        return $this->request('get', $path, $options);
    }

    private function request(string $method, string $path, array $options = []): array
    {
        $response = $this->client->request($method, $path, $this->prepareOptions($options));

        return $this->getResultAsArray($response);
    }

    private function prepareOptions(array $options = []): array
    {
        return array_merge_recursive($this->client->getConfig(), $options);
    }

    private function getResultAsArray(ResponseInterface $response): array
    {
        return [
            'status' => $response->getStatusCode(),
            'result' => json_decode($response->getBody(), true),
        ];
    }
}
