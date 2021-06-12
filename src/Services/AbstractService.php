<?php

namespace PostScripton\Money\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;

abstract class AbstractService implements ServiceInterface
{
	protected array $config;
	protected Client $client;

	protected const USER_AGENT = 'PostScripton/laravel-money';

	protected const FROM_TO_FORMAT = 1;
	protected const TO_FORMAT = 2;

	protected const DATE_FORMAT = 'Y-m-d';

	protected string $currencies = 'symbols';
	protected string $base = 'base';
	protected string $result = 'rates';

	public function __construct(array $config)
	{
		$this->config = $config;
		$this->boot();
	}

	public function getClassName(): string
	{
		return static::class;
	}

	public function boot(): void
	{
		$this->registerClient();
	}

	protected function registerClient(): void
	{
		$base = [
			'base_uri' => $this->url(),
			'headers' => [
				'User-Agent' => self::USER_AGENT,
			],
			'query' => $this->baseQuery(),
			'http_errors' => false,
		];

		$this->client = new Client($base);
	}

	protected function baseQuery(): array
	{
		return [
			'access_key' => $this->config['key'],
		];
	}

	public function url(): string
	{
		return $this->protocol() . '://' . trim($this->domain(), '/') . '/' . trim($this->uri(), '/');
	}

	protected function protocol(): string
	{
		if (!array_key_exists('secure', $this->config)) {
			return 'https';
		}

		return $this->config['secure'] ? 'https' : 'http';
	}

	protected function uri(): string
	{
		return '';
	}

	public function rate(string $from, string $to, ?Carbon $date = null): float
	{
		$options = [$this->currencies => implode(',', [$from, $to])];

		if ($this->doesNotHaveBaseRestriction()) {
			$options = array_merge($options, [$this->base => $from]);
		}

		$uri = is_null($date)
			? $this->latestUri()
			: $this->historicalUri($date, $options);

		$response = $this->client->get($uri, $this->query($options));
		$data = json_decode($response->getBody()->getContents(), true);

		$this->validateResponse($data);

		return $this->latestData($data, $this->result($from, $to)) /
			$this->latestData($data, $this->result($from, $from));
	}

	public function supports(string $iso): bool
	{
		$response = $this->client->get($this->supportedUri());
		$data = json_decode($response->getBody()->getContents(), true);

		$this->validateResponse($data);

		return in_array($iso, array_keys($this->supportedData($data, $this->currencies)));
	}

	protected static function BASE_CURRENCY(): string
	{
		return 'USD';
	}

	protected function resultFormat(): int
	{
		return self::TO_FORMAT;
	}

	protected function supportedData(array $data, string $index): array
	{
		return $data[$index];
	}

	protected function latestData(array $data, string $index): float
	{
		return $data[$this->result][$index];
	}

	private function hasBaseRestriction(): bool
	{
		if (!array_key_exists('base_restriction', $this->config)) {
			return false;
		}

		return $this->config['base_restriction'];
	}

	private function doesNotHaveBaseRestriction(): bool
	{
		return !$this->hasBaseRestriction();
	}

	private function result(string $from, string $to): string
	{
		if ($this->resultFormat() === self::TO_FORMAT) {
			return $to;
		}

		return $this->hasBaseRestriction()
			? static::BASE_CURRENCY() . $to
			: $from . $to;
	}

	private function query(array $options): array
	{
		return [
			'query' => array_merge($this->client->getConfig('query'), $options)
		];
	}

	abstract protected function domain(): string;

	abstract protected function supportedUri(): string;

	abstract protected function latestUri(): string;

	abstract protected function historicalUri(Carbon $date, array &$query): string;


	abstract protected function validateResponse(array $data): void;
}