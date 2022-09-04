<?php

namespace PostScripton\Money\Enums;

enum CurrencyList: string
{
    case All = 'all';
    case Popular = 'popular';
    case Custom = 'custom';

    public function path(): string
    {
        return __DIR__ . '/../Lists/' . $this->value . '_currencies.php';
    }
}
