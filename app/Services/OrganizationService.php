<?php

namespace App\Services;


use App\Repositories\OrganizationPhoneRepository\OrganizationPhoneRepositoryInterface;
use App\Repositories\OrganizationRepository\OrganizationRepositoryInterface;

class OrganizationService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private OrganizationPhoneRepositoryInterface $organizationPhoneRepository
    ) {
    }
    /**
     * Получить список организаций с учётом фильтров.
     *
     * @param ListOrganizationsRequest $request Параметры списка
     * @return ListOrganizationsResponse DTO-ответ со списком организаций
     *
     * @throws ModelNotFoundException Если фильтрующее здание или деятельность не найдены
     */
    public function listOrganizations(ListOrganizationsRequest $request): ListOrganizationsResponse
    {
        if ($request->buildingID !== null) {
            $this->buildingDomainService->first($request->buildingID);
        }

        $activityFilterIDs = [];
        if ($request->activityID !== null) {
            $activity = $this->activityDomainService->first($request->activityID);
            $activityFilterIDs = $this->activityDomainService->getDescendantIDs($activity->id);
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

        $searchResult = $this->organizationDomainService->search(
            $filters,
            $request->page,
            $request->perPage,
        );

        $organizations = $searchResult->organizations;
        $organizationIDs = $organizations->pluck('id')->all();
        /** @var list<int> $buildingIDs */
        $buildingIDs = $organizations->pluck('building_id')->unique()->values()->all();

        $buildingMap = $this->buildingDomainService->getByIDs($buildingIDs)->keyBy('id');
        $phonesMap = $this->organizationDomainService->getPhonesByOrganizationIDs($organizationIDs);
        $activitiesMap = $this->organizationDomainService->getActivityIDsByOrganizationIDs($organizationIDs);

        $activityIDs = [];
        foreach ($activitiesMap as $ids) {
            foreach ($ids as $id) {
                $activityIDs[$id] = true;
            }
        }
        $activityList = $this->activityDomainService->getByIDs(array_keys($activityIDs))->keyBy('id');

        $items = [];
        foreach ($organizations as $organization) {
            $building = $buildingMap->get($organization->building_id);
            if ($building === null) {
                continue;
            }

            $buildingDTO = new BuildingDTO(
                $building->id,
                $building->name,
                $building->address,
                (float)$building->latitude,
                (float)$building->longitude,
            );

            $activityDTOs = $this->makeActivityDTOs($activitiesMap[$organization->id] ?? [], $activityList);
            $phoneDTOs = $this->makePhoneDTOs($phonesMap[$organization->id] ?? []);

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

        $pages = (int)ceil($searchResult->total / max(1, $request->perPage));
        if ($searchResult->total === 0) {
            $pages = 0;
        }

        $pagination = new PaginationMeta(
            $request->page,
            $request->perPage,
            $searchResult->total,
            $pages,
        );

        return new ListOrganizationsResponse($items, $pagination);
    }

    /**
     * Получить детальную информацию об организации.
     *
     * @param int $organizationID ID организации
     * @return OrganizationResponse DTO-ответ с данными организации
     *
     * @throws ModelNotFoundException Если организация не найдена
     */
    public function getOrganization(int $organizationID): OrganizationResponse
    {
        $organization = $this->organizationDomainService->first($organizationID);
        $building = $this->buildingDomainService->first($organization->building_id);

        $phonesMap = $this->organizationDomainService->getPhonesByOrganizationIDs([$organizationID]);
        $activitiesMap = $this->organizationDomainService->getActivityIDsByOrganizationIDs([$organizationID]);
        $activityIDs = $activitiesMap[$organizationID] ?? [];

        $activityList = $this->activityDomainService->getByIDs($activityIDs)->keyBy('id');

        $buildingDTO = new BuildingDTO(
            $building->id,
            $building->name,
            $building->address,
            (float)$building->latitude,
            (float)$building->longitude,
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

    /**
     * Сформировать DTO видов деятельности.
     *
     * @param list<int> $activityIDs Список ID видов деятельности
     * @param SupportCollection<int, mixed> $activityList Коллекция видов деятельности, индексированная по ID
     * @return list<ActivityDTO> Массив DTO видов деятельности
     */
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
                $activity->depth,
                $activity->parent_id,
            );
        }

        return $activityDTOs;
    }

    /**
     * Сформировать DTO телефонов организации.
     *
     * @param list<OrganizationPhone> $phones Телефоны организации
     * @return list<OrganizationPhoneDTO> Массив DTO телефонов
     */
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
