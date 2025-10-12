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
     * @param int $buildingID
     * @return Collection
     */
    public function listByBuildingID(int $buildingID): Collection
    {
        return Organization::query()->where('building_id', $buildingID)->get();
    }

    /**
     * @param string|null $name
     * @param array|null $buildingIDs
     * @param array|null $activityID
     * @return Collection
     */
    public function filter(?string $name, ?array $buildingIDs, int|null $activityID): Collection
    {
        $q = Organization::query();

        if ($name) {
            $q->where('name', 'LIKE', '%' . str_replace('%', '\%', $name) . '%');
        }

        if (!empty($buildingIDs)) {
            $q->whereIn('building_id', $buildingIDs);
        }

        if (!empty($activityID)) {
            $q->whereExists(function ($sub) use ($activityID) {
                $sub->from('organization_activity as oa')
                    ->whereColumn('oa.organization_id', 'organizations.id')
                    ->where('oa.activity_id', $activityID);
            });
        }

        return $q->get();
    }
}
