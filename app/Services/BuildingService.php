<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Building;
use App\Repositories\BuildingRepository\BuildingRepositoryInterface;
use App\Services\DTO\Building\BuildingItem;
use App\Services\DTO\Building\GetBuildingResponse;
use App\Services\DTO\Building\ListBuildingsResponse;
use Illuminate\Database\Eloquent\Collection;

class BuildingService
{
    public function __construct(private BuildingRepositoryInterface $buildingRepository)
    {
    }

    public function listBuildings(): ListBuildingsResponse
    {
        $records = $this->buildingRepository->list();
        return $this->makeListResponse($records);
    }

    public function getBuilding(int $buildingID): GetBuildingResponse
    {
        $building = $this->buildingRepository->getByID($buildingID);
        return new GetBuildingResponse($this->makeItem($building));
    }

    /** Бросает ModelNotFoundException */
    public function first(int $buildingID): Building
    {
        return $this->buildingRepository->first($buildingID);
    }

    /** @return \Illuminate\Support\Collection<int, Building> keyBy('id') удобно делать выше */
    public function getByIDs(array $ids): Collection
    {
        return $this->buildingRepository->getByIDs($ids);
    }

    private function makeListResponse(iterable $records): ListBuildingsResponse
    {
        $items = [];
        foreach ($records as $record) {
            $items[] = $this->makeItem($record);
        }
        return new ListBuildingsResponse($items);
    }

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
