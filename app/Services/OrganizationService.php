<?php

namespace App\Services;

use App\Repositories\OrganizationActivityRepository\OrganizationActivityRepositoryInterface;
use App\Repositories\OrganizationPhoneRepository\OrganizationPhoneRepositoryInterface;
use App\Repositories\OrganizationRepository\OrganizationRepositoryInterface;
use App\Services\DTO\Activity\ActivityItem;
use App\Services\DTO\Organization\ListOrganizationsInBuildingResult;
use App\Services\DTO\Organization\OrganizationItem;
use Illuminate\Support\Collection;

/**
 * Сервис для работы с организациями.
 */
class OrganizationService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private OrganizationPhoneRepositoryInterface $organizationPhoneRepository,
        private OrganizationActivityRepositoryInterface $organizationActivityRepository,
        private BuildingService $buildingService,
        private ActivityService $activityService,
    ) {
    }


    /**
     * Получить список организаций в конкретном здании, включая их телефоны и виды деятельности.
     *
     * @param int $buildingId
     * @return ListOrganizationsInBuildingResult|Collection
     */
    public function listOrganizationsInBuilding(int $buildingId): ListOrganizationsInBuildingResult|Collection
    {
        $this->buildingService->first($buildingId);

        $organizations = $this->getOrganizationsByBuilding($buildingId);
        if ($organizations->isEmpty()) {
            return collect();
        }

        $organizationIds = $organizations->pluck('id')->all();

        $phonesByOrg = $this->getPhonesGroupedByOrganization($organizationIds);
        $activitiesByOrg = $this->getActivitiesGroupedByOrganization($organizationIds);

        $items = $organizations->map(function ($org) use ($phonesByOrg, $activitiesByOrg) {
            $phones = $phonesByOrg[$org->id] ?? [];
            $activities = $activitiesByOrg[$org->id] ?? [];

            return new OrganizationItem(
                organizationID: $org->id,
                name: $org->name,
                buildingID: $org->building_id,
                description: $org->description,
                email: $org->email,
                phones: $phones,
                activities: $activities,
                createdAt: $org->created_at,
                updatedAt: $org->updated_at,
            );
        })->all();

        return new ListOrganizationsInBuildingResult($items);
    }


    /**
     * Получить список организаций по ID здания.
     *
     * @param int $buildingId
     * @return Collection
     */
    protected function getOrganizationsByBuilding(int $buildingId): Collection
    {
        return $this->organizationRepository->listByBuildingID($buildingId);
    }

    /**
     * Получить телефоны организаций и сгруппировать их по ID организации.
     *
     * @param array $organizationIds
     * @return array
     */
    protected function getPhonesGroupedByOrganization(array $organizationIds): array
    {
        return $this->organizationPhoneRepository
            ->getPhonesByOrganizationIDs($organizationIds)
            ->groupBy('organization_id')
            ->map(fn($group) => $group->pluck('phone')->values()->all())
            ->toArray();
    }


    /**
     * Получить виды деятельности организаций и сгруппировать их по ID организации.
     *
     * @param array $organizationIds
     * @return array
     */
    protected function getActivitiesGroupedByOrganization(array $organizationIds): array
    {
        $links = $this->organizationActivityRepository->getOrganizationActivityByOrganizationIDs($organizationIds);
        $activityIds = $links->pluck('activity_id')->unique()->values()->all();
        $activities = $this->activityService->getByIDs($activityIds)->keyBy('id');

        $byOrg = [];
        foreach ($links as $link) {
            $actId = $link->activity_id;
            if ($activities->has($actId)) {
                $act = $activities->get($actId);
                $byOrg[$link->organization_id][] = new ActivityItem($act->id, $act->name, $act->level, $act->parent_id);
            }
        }

        foreach ($byOrg as $orgId => $list) {
            $byOrg[$orgId] = array_values($list);
        }

        return $byOrg;
    }
}
