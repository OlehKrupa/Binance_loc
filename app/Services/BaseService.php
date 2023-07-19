<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Type\Integer;

abstract class BaseService
{
    public $repo;

    /**
     * Get all records.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return $this->repo->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        return $this->repo->create($data);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id): Model
    {
        return $this->repo->findById($id);
    }

    /**
     * Update a record by ID.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->repo->update($id, $data);
    }

    /**
     * Delete a record by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}