<?php

namespace PostScripton\Money\Exceptions;

class CustomCurrencyTakenCodesException extends BaseException
{
	public function __construct(string $value, $code = 0, BaseException $previous = null)
	{
		list($name, $iso, $num) = explode(',', $value);

		parent::__construct(
			"Some custom currency uses the taken codes of the already existing currency: \"{$name}\" ({$iso}|{$num})",
			$code,
			$previous
		);
	}
}