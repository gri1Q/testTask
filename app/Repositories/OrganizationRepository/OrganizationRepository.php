<?php

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use App\Repositories\OrganizationPhoneRepository\OrganizationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
}
