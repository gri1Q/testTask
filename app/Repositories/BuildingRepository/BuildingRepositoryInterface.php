<?php

namespace App\Repositories\BuildingRepository;

use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;

interface BuildingRepositoryInterface
{

    /**
     * Получить здание по ID.
     *
     * @param int $id
     * @return Building
     */
    public function getByID(int $id): Building;

    /**
     * Получить все здания.
     *
     * @return Collection
     */
    public function list(): Collection;
}
