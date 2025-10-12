<?php

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

interface OrganizationRepositoryInterface
{
    /**
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
     * @param string|null $name
     * @param array|null $buildingIDs
     * @param int|null $activityID
     * @return Collection
     */
    public function filter(?string $name, ?array $buildingIDs, ?int $activityID): Collection;
}
