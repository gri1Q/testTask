<?php

declare(strict_types=1);

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

interface OrganizationRepositoryInterface
{
    /**
     * Найти организацию по ID.
     *
     * @param int $id
     * @return Organization
     */
    public function first(int $id): Organization;

    /**
     * Получить организации по ID здания.
     *
     * @param int $buildingID
     * @return Collection
     */
    public function listByBuildingID(int $buildingID): Collection;

    /**
     * Отфильтровать организации по нескольким критериям.
     *
     * @param string|null $name
     * @param array|null $buildingIDs
     * @param int|null $activityID
     * @return Collection
     */
    public function filter(?string $name, ?array $buildingIDs, ?int $activityID): Collection;
}
