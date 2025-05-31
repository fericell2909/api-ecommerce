<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository
{
    public $supplier;
    public function __construct()
    {
        $this->supplier = new Supplier();
        //$this->company = new User();
    }
    public function bulk(array $arr, $rol)
    {
        return $this->supplier->bulk($arr, $rol);
    }

    public function list(array $arr, $rol)
    {
        return $this->supplier->list($arr, $rol);
    }

    public function updatefavorite(array $arr, $rol)
    {
        return $this->supplier->updatefavorite($arr, $rol);
    }

    public function get($uuid, $rol)
    {
        return $this->supplier->get($uuid, $rol);
    }

    public function createorupdate(array $arr)
    {
        return $this->supplier->createorupdate($arr);
    }

    public function changestatus(array $arr, $rol)
    {
        return $this->supplier->changestatus($arr, $rol);
    }

    public function autocomplete($arr, $rol)
    {
        return $this->supplier->autocomplete($arr, $rol);
    }

    public function report($arr, $rol)
    {
        return $this->supplier->report($arr, $rol);
    }

    public function suppliers($arr, $rol)
    {
        return $this->supplier->suppliers($arr, $rol);
    }
}
