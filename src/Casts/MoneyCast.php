<?php

namespace PostScripton\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use PostScripton\Money\Money;

class MoneyCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if (is_null($value)) {
            return null;
        }

        return money($value);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        $isMonetary = gettype($value) === 'object' && $value instanceof Money;
        if (! $isMonetary) {
            $value = money_parse($value);
        }

        return $value->getAmount();
    }
}
