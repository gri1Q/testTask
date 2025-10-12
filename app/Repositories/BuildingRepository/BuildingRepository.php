<?php

declare(strict_types=1);

namespace App\Repositories\BuildingRepository;

use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;

class BuildingRepository implements BuildingRepositoryInterface
{
    /**
     * Получить все здания.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return Building::query()->orderBy('id')->get();
    }

    /**
     * @param int $id
     * @return Building
     */
    public function getByID(int $id): Building
    {
        return Building::query()->where('id', $id)->firstOrFail();
    }

    /**
     * @param int $id
     * @return Building
     */
    public function first(int $id): Building
    {
        return Building::query()->findOrFail($id);
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function getByIDs(array $ids): Collection
    {
        return Building::query()->whereIn('id', $ids)->get();
    }
}
