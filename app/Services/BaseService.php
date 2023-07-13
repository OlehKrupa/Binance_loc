<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Type\Integer;

abstract class BaseService
{
    public $repo;

    public function all(): Collection
    {
        return $this->repo->all();
    }

    public function create(array $data): Model
    {
        return $this->repo->create($data);
    }

    public function findById(int $id): Model
    {
        return $this->repo->findById($id);
    }

    public function update(int $id, array $data): bool
    {
        return $this->repo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }

    /*
    public function count(): int
    {
        return $this->repo->count();
    }
    */
}
