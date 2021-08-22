<?php

namespace PostScripton\Money\Exceptions;

class CustomCurrencyWrongFieldTypeException extends BaseException
{
    public function __construct(string $value, $code = 0, BaseException $previous = null)
    {
        list($field, $rules) = explode(':', $value);
        $rules = explode('|', $rules);
        $text_rules = implode(' or ', $rules);

        $text_rules = count($rules) > 1 ? 'either ' : '' . $text_rules;

        parent::__construct(
            "Some custom currency field \"{$field}\" must be " . $text_rules,
            $code,
            $previous
        );
    }
}
