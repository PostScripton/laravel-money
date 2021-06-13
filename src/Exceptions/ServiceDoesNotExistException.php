<?php

namespace PostScripton\Money\Exceptions;

class ServiceDoesNotExistException extends BaseException
{
	public function __construct(string $service, $code = 0, BaseException $previous = null)
	{
		parent::__construct(
			"The service \"{$service}\" doesn't exist in the \"services\" property.",
			$code,
			$previous
		);
	}
}