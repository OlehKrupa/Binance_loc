<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

abstract class BaseRepository implements BaseInterface
{
    protected $model;

    public $sortBy = 'id';
    public $sortOrder = 'desc';

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve all records from the model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return $this->model
            ->orderBy($this->sortBy, $this->sortOrder)
            ->get();
    }

    /**
     * Create a new record in the model.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a record in the model by ID.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $query = $this->model->where('id', $id)->first();
        return $query->update($data);
    }

    /**
     * Delete a record from the model by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->model->destroy($id);
        return true;
    }

    /**
     * Find a record in the model by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Get the underlying model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Set the model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
        return true;
    }
}