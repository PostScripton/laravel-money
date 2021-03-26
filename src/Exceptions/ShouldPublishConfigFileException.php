<?php

namespace PostScripton\Money\Exceptions;

use Exception;

class ShouldPublishConfigFileException extends Exception
{
	/**
	 * @param int $code
	 * @param Exception|null $previous
	 */
	public function __construct($code = 0, Exception $previous = null)
	{
		parent::__construct('Please publish the config file by running \'php artisan vendor:publish --tag=money\'', $code, $previous);
	}
}