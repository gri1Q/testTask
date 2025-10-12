<?php

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use App\Services\DTO\Organization\OrganizationSearchFilters;
use App\Services\DTO\Organization\SearchOrganizationsResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public function create(Organization $organization): void
    {
        $organization->save();
    }

    public function first(int $id): Organization
    {
        return Organization::query()->findOrFail($id);
    }

    public function getByIDs(array $ids): Collection
    {
        return Organization::query()->whereIn('id', $ids)->get();
    }

    public function search(
        OrganizationSearchFilters $filters,
        int $page,
        int $perPage
    ): SearchOrganizationsResult {
        $qb = Organization::query()
            ->select('organizations.*')
            ->when($filters->search, fn(Builder $q, $s) =>
            $q->where('organizations.name', 'like', "%{$s}%")
                ->orWhere('organizations.address', 'like', "%{$s}%")
            )
            ->when($filters->buildingID, fn(Builder $q, $bId) =>
            $q->where('organizations.building_id', $bId)
            );

        if (!empty($filters->activityIDs)) {
            $qb->join('organization_activity as oa', 'oa.organization_id', '=', 'organizations.id')
                ->whereIn('oa.activity_id', $filters->activityIDs);
        }

        if ($filters->latitude !== null && $filters->longitude !== null && $filters->radiusKm !== null) {
            $lat = (float)$filters->latitude;
            $lng = (float)$filters->longitude;
            $qb->selectRaw(
                '(6371 * 2 * ASIN(SQRT(
                    POWER(SIN(RADIANS(? - organizations.latitude)/2), 2) +
                    COS(RADIANS(organizations.latitude)) * COS(RADIANS(?)) *
                    POWER(SIN(RADIANS(? - organizations.longitude)/2), 2)
                ))) as distance_km',
                [$lat, $lat, $lng]
            )
                ->having('distance_km', '<=', (float)$filters->radiusKm)
                ->orderBy('distance_km');
        }

        $organizations = $qb
            ->groupBy('organizations.id')
            ->get();

        return new SearchOrganizationsResult($organizations);
    }
}
