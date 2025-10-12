<?php

declare(strict_types=1);

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
     * @param int $buildingID
     * @return ListOrganizationsInBuildingResult|Collection
     */
    public function listOrganizationsInBuilding(int $buildingID): ListOrganizationsInBuildingResult|Collection
    {
        $this->buildingService->first($buildingID);

        $organizations = $this->getOrganizationsByBuilding($buildingID);
        if ($organizations->isEmpty()) {
            return collect();
        }

        $organizationIDs = $organizations->pluck('id')->all();

        $phonesByOrg = $this->getPhonesGroupedByOrganization($organizationIDs);
        $activitiesByOrg = $this->getActivitiesGroupedByOrganization($organizationIDs);

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
     * Получить одну организацию по её идентификатору, включая телефоны и виды деятельности.
     *
     * @param int $organizationID
     * @return OrganizationItem
     */
    public function getOrganization(int $organizationID): OrganizationItem
    {
        $organization = $this->organizationRepository->first($organizationID) ?? null;

        $phones = $this->organizationPhoneRepository
            ->getPhonesByOrganizationIDs([$organization->id])
            ->pluck('phone')
            ->values()
            ->all();

        $links = $this->organizationActivityRepository->getOrganizationActivityByOrganizationIDs([$organization->id]);
        $activityIDs = $links->pluck('activity_id')->unique()->values()->all();

        $activities = collect();
        if (!empty($activityIDs)) {
            $activities = $this->activityService
                ->getByIDs($activityIDs)
                ->map(fn($act) => new ActivityItem(
                    $act->id,
                    $act->name,
                    $act->level,
                    $act->parent_id
                ))
                ->values();
        }

        return new OrganizationItem(
            organizationID: $organization->id,
            name: $organization->name,
            buildingID: $organization->building_id,
            description: $organization->description,
            email: $organization->email,
            phones: $phones,
            activities: $activities->all(),
            createdAt: $organization->created_at,
            updatedAt: $organization->updated_at,
        );
    }

    /**
     * @param string|null $name
     * @param int|null $activityID
     * @param int|null $buildingID
     * @return ListOrganizationsInBuildingResult|Collection
     */
    public function filterOrganizations(
        ?string $name = null,
        ?int $activityID = null,
        ?int $buildingID = null,
    ): ListOrganizationsInBuildingResult {
        $buildingIDs = null;
        if ($buildingID !== null) {
            $this->buildingService->first($buildingID);
            $buildingIDs = [$buildingID];
        }
        $organizations = $this->organizationRepository->filter(
            $name,
            $buildingIDs,
            $activityID,
        );

        if ($organizations->isEmpty()) {
            return new ListOrganizationsInBuildingResult([]);
        }

        $organizationIDs = $organizations->pluck('id')->all();
        $phonesByOrg = $this->getPhonesGroupedByOrganization($organizationIDs);
        $activitiesByOrg = $this->getActivitiesGroupedByOrganization($organizationIDs);

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
     * @param int $buildingID
     * @return Collection
     */
    private function getOrganizationsByBuilding(int $buildingID): Collection
    {
        return $this->organizationRepository->listByBuildingID($buildingID);
    }

    /**
     * Получить телефоны организаций и сгруппировать их по ID организации.
     *
     * @param array $organizationIDs
     * @return array
     */
    private function getPhonesGroupedByOrganization(array $organizationIDs): array
    {
        return $this->organizationPhoneRepository
            ->getPhonesByOrganizationIDs($organizationIDs)
            ->groupBy('organization_id')
            ->map(fn($group) => $group->pluck('phone')->values()->all())
            ->toArray();
    }


    /**
     * Получить виды деятельности организаций и сгруппировать их по ID организации.
     *
     * @param array $organizationIDs
     * @return array
     */
    private function getActivitiesGroupedByOrganization(array $organizationIDs): array
    {
        $links = $this->organizationActivityRepository->getOrganizationActivityByOrganizationIDs($organizationIDs);
        $activityIDs = $links->pluck('activity_id')->unique()->values()->all();
        $activities = $this->activityService->getByIDs($activityIDs)->keyBy('id');

        $byOrg = [];
        foreach ($links as $link) {
            $actID = $link->activity_id;
            if ($activities->has($actID)) {
                $act = $activities->get($actID);
                $byOrg[$link->organization_id][] = new ActivityItem($act->id, $act->name, $act->level, $act->parent_id);
            }
        }

        foreach ($byOrg as $orgID => $list) {
            $byOrg[$orgID] = array_values($list);
        }

        return $byOrg;
    }
}
