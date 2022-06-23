<?php

namespace PostScripton\Money\Rules;

use Illuminate\Contracts\Validation\Rule;

class Money implements Rule
{
    public const RULE_NAME = 'money';

    private const MONEY_REGEX = '/^(([1-9]\d{0,2})(\d*|(\s\d{3})*)|0)(\.\d{1,4})?$/';

    public function passes($attribute, $value): bool
    {
        return preg_match(self::MONEY_REGEX, $value) > 0;
    }

    public function message(): string
    {
        return 'The :attribute must contain only digits and a dot to represent cents.';
    }
}
