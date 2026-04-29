<?php
namespace App\Repositories\Contracts;

interface BookingRepositoryInterface extends BaseRepositoryInterface
{
    public function getList(array $filters = []);
    public function getOne(array $filters);
}