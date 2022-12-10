<?php

namespace PostScripton\Money\Tests\Feature\Clients;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use PostScripton\Money\Clients\AbstractClient;
use PostScripton\Money\MoneyServiceProvider;
use PostScripton\Money\Tests\TestCase;

class AbstractClientTest extends TestCase
{
    public function testClientConfig(): void
    {
        $client = $this->getClient([
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer XXX',
            ],
            RequestOptions::QUERY => [
                'lang' => 'en',
            ],
        ]);
        $expectedConfig = [
            'base_uri' => new Uri($client->baseUri()),
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
                'Accepts' => 'application/json',
                'User-Agent' => MoneyServiceProvider::FULL_PACKAGE_NAME_WITH_VERSION,
                'Authorization' => 'Bearer XXX',
            ],
            RequestOptions::QUERY => [
                'lang' => 'en',
            ],
        ];

        $config = array_intersect_key($client->getClientConfig(), [
            'base_uri' => '',
            RequestOptions::HEADERS => '',
            RequestOptions::QUERY => '',
        ]);

        $this->assertEquals($expectedConfig, $config);
    }

    public function testGetRequest(): void
    {
        $mock = new MockHandler([
            new Response(200, body: '{"key": "value"}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = $this->getClient([
            RequestOptions::QUERY => [
                'lang' => 'en',
            ],
            'handler' => $handlerStack,
        ]);
        $expectedResponse = [
            'status' => 200,
            'result' => [
                'key' => 'value',
            ],
        ];

        $response = $client->testGetRequest('/path', [RequestOptions::QUERY => ['q' => 'something']]);

        $this->assertEquals($expectedResponse, $response);
    }

    private function getClient(array $config = []): AbstractClient
    {
        return new class ($config) extends AbstractClient
        {
            public function getClientConfig(): array
            {
                return $this->client->getConfig();
            }

            public function testGetRequest(string $path, array $options = []): array
            {
                return $this->getRequest($path, $options);
            }

            public function baseUri(): string
            {
                return 'test.com';
            }
        };
    }
}
