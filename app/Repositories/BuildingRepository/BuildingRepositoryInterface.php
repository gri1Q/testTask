<?php

declare(strict_types=1);

namespace App\Repositories\BuildingRepository;

use App\Models\Building;
use Illuminate\Database\Eloquent\Collection;

interface BuildingRepositoryInterface
{
    /**
     * Получить все здания.
     *
     * @return Collection<Building>
     */
    public function list(): Collection;

    /**
     * Получить здание по ID (404 при отсутствии).
     *
     * @param int $id
     * @return Building
     */
    public function getByID(int $id): Building;

    /**
     * Найти здание по ID (404 при отсутствии).
     * Аналог findOrFail для единообразия с сервисом.
     *
     * @param int $id
     * @return Building
     */
    public function first(int $id): Building;

    /**
     * Получить здания по списку ID.
     *
     * @param array<int> $ids
     * @return Collection<Building>
     */
    public function getByIDs(array $ids): Collection;
}
