<?php

namespace App\Services;

use App\Repositories\OrganizationPhoneRepository\OrganizationPhoneRepositoryInterface;
use App\Repositories\OrganizationRepository\OrganizationRepositoryInterface;
use App\Services\DTO\Activity\ActivityDTO;
use App\Services\DTO\Building\BuildingItem;
use Illuminate\Support\Collection as SupportCollection;

class OrganizationService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private OrganizationPhoneRepositoryInterface $organizationPhoneRepository,
        private ActivityService $activityService,
        private BuildingService $buildingService,
    ) {}

    public function listOrganizations(ListOrganizationsRequest $request): ListOrganizationsResponse
    {
        if ($request->buildingID !== null) {
            $this->buildingService->first($request->buildingID);
        }

        $activityFilterIDs = [];
        if ($request->activityID !== null) {
            $activity = $this->activityService->first($request->activityID);
            $activityFilterIDs = $this->activityService->getDescendantIDs($activity->id);
            array_unshift($activityFilterIDs, $activity->id);
            $activityFilterIDs = array_values(array_unique($activityFilterIDs));
        }

        $filters = new OrganizationSearchFilters(
            $request->buildingID,
            $activityFilterIDs,
            $request->search,
            $request->latitude,
            $request->longitude,
            $request->radiusKm,
        );

        $searchResult = $this->organizationRepository->search(
            $filters,
            $request->page,
            $request->perPage,
        );

        $organizations = $searchResult->organizations; // Collection<Organization>
        $organizationIDs = $organizations->pluck('id')->all();
        $buildingIDs = $organizations->pluck('building_id')->unique()->values()->all();

        $buildings = $this->buildingService->getByIDs($buildingIDs)->keyBy('id');
        $phones = $this->organizationPhoneRepository->getPhonesByOrganizationIDs($organizationIDs);
        $activities = $this->activityService->getActivityIDsByOrganizationIDs($organizationIDs);

        $activityIDs = [];
        foreach ($activities as $ids) {
            foreach ($ids as $id) {
                $activityIDs[$id] = true;
            }
        }
        $activityList = $this->activityService->getByIDs(array_keys($activityIDs))->keyBy('id');

        $items = [];
        foreach ($organizations as $organization) {
            $building = $buildings->get($organization->building_id);
            if ($building === null) {
                continue;
            }

            $buildingDTO = new BuildingItem(
                $building->id,
                $building->address,
                (float)$building->latitude,
                (float)$building->longitude,
                $building->created_at,
                $building->updated_at,
            );

            $activityDTOs = $this->makeActivityDTOs($activities[$organization->id] ?? [], $activityList);
            $phoneDTOs = $this->makePhoneDTOs($phones[$organization->id] ?? []);

            $distance = $organization->getAttribute('distance_km');
            $distanceValue = $distance !== null ? (float)$distance : null;

            $items[] = new OrganizationListItem(
                $organization->id,
                $organization->name,
                $organization->address,
                (float)$organization->latitude,
                (float)$organization->longitude,
                $buildingDTO,
                $activityDTOs,
                $phoneDTOs,
                $distanceValue,
            );
        }

        return new ListOrganizationsResponse($items);
    }

    public function getOrganization(int $organizationID): OrganizationResponse
    {
        $organization = $this->organizationRepository->first($organizationID);
        $building = $this->buildingService->first($organization->building_id);

        $phonesMap = $this->organizationPhoneRepository->getPhonesByOrganizationIDs([$organizationID]);
        $activitiesMap = $this->activityService->getActivityIDsByOrganizationIDs([$organizationID]);
        $activityIDs = $activitiesMap[$organizationID] ?? [];

        $activityList = $this->activityService->getByIDs($activityIDs)->keyBy('id');

        $buildingDTO = new BuildingItem(
            $building->id,
            $building->address,
            (float)$building->latitude,
            (float)$building->longitude,
            $building->created_at,
            $building->updated_at,
        );

        $activityDTOs = $this->makeActivityDTOs($activityIDs, $activityList);
        $phoneDTOs = $this->makePhoneDTOs($phonesMap[$organizationID] ?? []);

        $organizationDTO = new OrganizationDTO(
            $organization->id,
            $organization->name,
            $organization->address,
            (float)$organization->latitude,
            (float)$organization->longitude,
            $buildingDTO,
            $activityDTOs,
            $phoneDTOs,
            $organization->description,
            $organization->website,
            null,
        );

        return new OrganizationResponse($organizationDTO);
    }

    /** @return list<ActivityDTO> */
    private function makeActivityDTOs(array $activityIDs, SupportCollection $activityList): array
    {
        $activityDTOs = [];
        foreach ($activityIDs as $activityID) {
            $activity = $activityList->get($activityID);
            if ($activity === null) {
                continue;
            }
            $activityDTOs[] = new ActivityDTO(
                $activity->id,
                $activity->name,
                $activity->level,
                $activity->parent_id,
            );
        }
        return $activityDTOs;
    }

    /** @return list<OrganizationPhoneDTO> */
    private function makePhoneDTOs(array $phones): array
    {
        $phoneDTOs = [];
        foreach ($phones as $phone) {
            $phoneDTOs[] = new OrganizationPhoneDTO(
                $phone->phone,
                $phone->description,
            );
        }
        return $phoneDTOs;
    }
}
