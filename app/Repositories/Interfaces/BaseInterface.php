<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BaseInterface
{
    public function findById(int $id);

    public function all();

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}