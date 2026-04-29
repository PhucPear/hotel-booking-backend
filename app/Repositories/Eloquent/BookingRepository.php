<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    private array $defaultRelations = ['details', 'payment'];

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Booking::create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $booking = $this->find($id);

            $booking->update($data);

            return $booking->fresh($this->defaultRelations);
        });
    }

    public function find(int $id, array $relations = [])
    {
        $relations = empty($relations) ? $this->defaultRelations : $relations;

        return Booking::with($relations)->findOrFail($id);
    }

    public function getList(array $filters = [])
    {
        $query = Booking::with($this->defaultRelations);

        $this->applyFilters($query, $filters);

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getOne(array $filters)
    {
        $query = Booking::with($this->defaultRelations);

        $this->applyFilters($query, $filters);

        return $query->first();
    }

    protected function applyFilters($query, array $filters)
    {
        // filter by user
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // filter by created_at
        if (!empty($filters['created_at'])) {
            $query->where('created_at', '>=', $filters['created_at']);
        }
    }
}
