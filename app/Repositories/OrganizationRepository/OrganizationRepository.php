<?php

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

/**
 * Репозиторий для сущности Organization.
 */
class OrganizationRepository implements OrganizationRepositoryInterface
{
    /**
     * Сохранение организации.
     *
     * @param Organization $organization
     * @return void
     */
    public function create(Organization $organization): void
    {
        $organization->save();
    }

    /**
     * Получить организацию по ID.
     *
     * @param int $id
     * @return Organization
     */
    public function getByID(int $id): Organization
    {
        return Organization::query()->findOrFail($id);
    }

    /**
     * Получить все организации.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Organization::all();
    }

    /**
     * Получить все организации в конкретном здании.
     *
     * @param int $buildingId
     * @return Collection
     */
    public function getByBuildingID(int $buildingId): Collection
    {
        return Organization::query()
            ->where('building_id', $buildingId)
            ->get();
    }

    /**
     * Поиск организаций по названию.
     *
     * @param string $name
     * @return Collection
     */
    public function searchByName(string $name): Collection
    {
        return Organization::query()
            ->where('name', $name)
            ->get();
    }

    public function inRadius(float $lat, float $lng, float $radiusKm): Collection
    {
        return Organization::query()
            ->select('organizations.*')
            ->join('buildings', 'buildings.id', '=', 'organizations.building_id')
            ->selectRaw(
                '(6371 * 2 * ASIN(SQRT(
                POWER(SIN(RADIANS(? - buildings.latitude)/2), 2) +
                COS(RADIANS(buildings.latitude)) * COS(RADIANS(?)) *
                POWER(SIN(RADIANS(? - buildings.longitude)/2), 2)
            ))) as distance',
                [$lat, $lat, $lng]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->get();
    }
}
