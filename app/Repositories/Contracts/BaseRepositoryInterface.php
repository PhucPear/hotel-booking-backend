<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function find(int $id);
    public function delete(int $id);
}
