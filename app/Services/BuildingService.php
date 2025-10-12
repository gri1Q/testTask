<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Building;
use App\Repositories\BuildingRepository\BuildingRepositoryInterface;
use App\Services\DTO\Building\BuildingItem;
use App\Services\DTO\Building\GetBuildingResponse;
use App\Services\DTO\Building\ListBuildingsResponse;

/**
 * Сервис для работы со зданиями.
 */
class BuildingService
{
    public function __construct(private BuildingRepositoryInterface $buildingRepository)
    {
    }

    /**
     * Получить список всех зданий.
     *
     * @return ListBuildingsResponse
     */
    public function listBuildings(): ListBuildingsResponse
    {
        $records = $this->buildingRepository->list();
        return $this->makeListResponse($records);
    }

    /**
     * Получить одно здание по ID.
     *
     * @param int $buildingID
     * @return GetBuildingResponse
     */
    public function getBuilding(int $buildingID): GetBuildingResponse
    {
        $building = $this->buildingRepository->getByID($buildingID);
        return new GetBuildingResponse($this->makeItem($building));
    }

    /**
     * Проверить существование здания и вернуть модель.
     *
     * @param int $buildingID
     * @return Building
     */
    public function first(int $buildingID): Building
    {
        return $this->buildingRepository->first($buildingID);
    }

    /**
     * Преобразовать список моделей зданий в транспортный ответ.
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
     * Преобразовать модель в транспортный DTO BuildingItem.
     *
     * @param Building $building
     * @return BuildingItem
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
