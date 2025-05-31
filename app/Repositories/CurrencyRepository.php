<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository
{
    public $currency;
    public function __construct()
    {
        $this->currency = new Currency();
    }

    public function currencies($arr, $rol)
    {
        return $this->currency->currencies($arr, $rol);
    }
}
