<?php

namespace App\Modules\CrudGenerator\Interfaces;

interface CrudInterface
{
    /**
     * Get All Data
     *
     * @return array All Data
     */
    public function getAll();

    /**
     * Get Paginated Data
     *
     * @param int   Page No
     * @return array Paginated Data
     */
    public function getPaginatedData(array $options);

    /**
     * Create New Item
     *
     * @param array $data
     * @return object Created
     */
    public function create(array $data);


    /**
     * Get Item Details By ID
     *
     * @param int $id
     * @return object Get
     */
    public function getByID(int $id);

    /**
     * Update Product By Id and Data
     *
     * @param int $id
     * @param array $data
     * @return object Updated Product Information
     */
    public function update(int $id, array $data);

    public function changestatus(int $id);
}
