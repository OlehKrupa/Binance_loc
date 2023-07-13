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

    public function all(): Collection
    {
        return $this->model
            ->orderBy($this->sortBy, $this->sortOrder)
            ->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $query = $this->model->where('id', $id)->first();
        return $query->update($data);
    }

    public function delete(int $id): bool
    {
        $this->model->destroy($id);
        return true;
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    } 

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
        return true;
    }
}
