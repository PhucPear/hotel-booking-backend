<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }
}
