<?php

namespace PostScripton\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MoneyCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return money($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value->getPureAmount();
    }
}
