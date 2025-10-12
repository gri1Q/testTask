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
     * @param int $buildingId
     * @return Collection
     */
    public function listByBuildingID(int $buildingId): Collection;
}
