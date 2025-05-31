<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository
{
    public $company;
    public function __construct()
    {
        $this->company = new Company();
        //$this->company = new User();
    }
    public function list(array $arr, $rol)
    {

        return $this->company->list($arr, $rol);
    }

    public function get(string $uuid, $rol)
    {
        return $this->company->get($uuid, $rol);
    }
    public function createorupdate(array $arr)
    {
        return $this->company->createorupdate($arr);
    }

    public function updateStatusUsersByCompany(array $arr)
    {
        return $this->company->updateStatusUsersByCompany($arr);
    }

    public function deleteuserByCompany(array $arr)
    {
        return $this->company->deleteuserByCompany($arr);
    }

    public function registeruserByCompany(array $arr)
    {
        return $this->company->registeruserByCompany($arr);
    }

    public function registeruserExistByCompany(array $arr)
    {
        return $this->company->registeruserExistByCompany($arr);
    }

    public function roles($rol)
    {
        return $this->company->roles($rol);
    }
    public function getUsers(array $arr, $rol)
    {
        return $this->company->getUsers($arr, $rol);
    }

    public function updatecompanysii(array $arr, $rol, $is_replacement_user, $id_replacement_user)
    {
        return $this->company->updatecompanysii($arr, $rol, $is_replacement_user, $id_replacement_user);
    }

    public function updatecompanysii_user_pass(array $arr, $rol, $is_replacement_user, $id_replacement_user)
    {
        return $this->company->updatecompanysii_user_pass($arr, $rol, $is_replacement_user, $id_replacement_user);
    }

    public function updatecompanysii_pin(array $arr, $rol, $is_replacement_user, $id_replacement_user)
    {
        return $this->company->updatecompanysii_pin($arr, $rol, $is_replacement_user, $id_replacement_user);
    }

    public function updatecompanysii_authorization_invoice(array $arr, $rol, $is_replacement_user, $id_replacement_user)
    {
        return $this->company->updatecompanysii_authorization_invoice($arr, $rol, $is_replacement_user, $id_replacement_user);
    }

    public function createorupdatesync(array $arr)
    {
        return $this->company->createorupdatesync($arr);
    }

    public function companies($arr, $rol)
    {
        return $this->company->companies($arr, $rol);
    }
    public function companyuser($arr, $rol, $id)
    {
        return $this->company->companyuser($arr, $rol, $id);
    }
}
