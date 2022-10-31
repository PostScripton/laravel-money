<?php

namespace PostScripton\Money\Enums;

use Illuminate\Support\Collection;

enum CurrencyList: string
{
    case All = 'all';
    case Popular = 'popular';
    case Custom = 'custom';

    public function collection(): Collection
    {
        $currencies = match ($this) {
            self::Custom => config('money.custom_currencies'),
            default => require $this->path(),
        };

        return collect($currencies);
    }

    private function path(): string
    {
        return sprintf('%s/../Lists/%s_currencies.php', __DIR__, $this->value);
    }
}
