<?php

namespace PostScripton\Money\Services;

interface ServiceInterface
{
	/**
	 * Runs at the booting of the service
	 */
	public function boot(): void;

	/**
	 * Currency exchange rate
	 * @param string $from
	 * @param string $to
	 * @return float
	 */
	public function rate(string $from, string $to): float;

	/**
	 * Whether the service supports currencies or not
	 * @param string $iso
	 * @return bool
	 */
	public function supports(string $iso): bool;

	/**
	 * Gives a full service class name with namespace
	 * @return string
	 */
	public function getClassName(): string;

	/**
	 * A base url for API requests
	 * @return string
	 */
	public function url(): string;
}