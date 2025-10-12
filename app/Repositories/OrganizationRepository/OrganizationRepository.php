<?php

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class OrganizationRepository implements OrganizationRepositoryInterface
{


    /**
     * @param int $id
     * @return Organization
     */
    public function first(int $id): Organization
    {
        return Organization::query()->findOrFail($id);
    }

    /**
     * Получить организации по ID здания.
     *
     * @param int $buildingId
     * @return Collection
     */
    public function listByBuildingID(int $buildingId): Collection
    {
        return Organization::query()->where('building_id', $buildingId)->get();
    }
}
