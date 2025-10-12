<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Building;
use App\Repositories\BuildingRepository\BuildingRepositoryInterface;
use App\Services\DTO\Building\BuildingItem;
use App\Services\DTO\Building\GetBuildingResponse;
use App\Services\DTO\Building\ListBuildingsResponse;
use App\Services\DTO\Building\SearchBuildingsByRadiusRequest;

class BuildingService
{
    public function __construct(private BuildingRepositoryInterface $buildingRepository)
    {
    }

    /**
     * Получить список зданий.
     */
    public function listBuildings(): ListBuildingsResponse
    {
        $records = $this->buildingRepository->list();

        return $this->makeListResponse($records);
    }

    /**
     * Получить здание по идентификатору.
     */
    public function getBuilding(int $buildingID): GetBuildingResponse
    {
        $building = $this->buildingRepository->getByID($buildingID);

        return new GetBuildingResponse($this->makeItem($building));
    }

    /**
     * Найти здания в пределах радиуса.
     */
    public function searchWithinRadius(SearchBuildingsByRadiusRequest $request): ListBuildingsResponse
    {
        $records = $this->buildingRepository->searchWithinRadius(
            $request->latitude,
            $request->longitude,
            $request->radiusMeters,
        );

        return $this->makeListResponse($records);
    }


    /**
     * Собрать ответ со списком зданий.
     *
     * @param iterable $records
     * @return ListBuildingsResponse
     */
    private function makeListResponse(iterable $records): ListBuildingsResponse
    {
        $items = [];

        foreach ($records as $record) {
            $items[] = $this->makeItem($record);
        }

        return new ListBuildingsResponse($items);
    }

    /**
     * Построить сервисный DTO здания.
     */
    private function makeItem(Building $building): BuildingItem
    {
        return new BuildingItem(
            $building->id,
            (string)$building->address,
            (float)$building->latitude,
            (float)$building->longitude,
            $building->created_at,
            $building->updated_at,
        );
    }
}
