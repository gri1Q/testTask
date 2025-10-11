<?php

namespace App\Repositories\OrganizationRepository;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

interface OrganizationRepositoryInterface
{
    /**
     * Создание организации.
     *
     * @param Organization $organization
     * @return void
     */
    public function create(Organization $organization): void;

    /**
     * Получить организацию по ID.
     *
     * @param int $id
     * @return Organization
     */
    public function getByID(int $id): Organization;

    /**
     * Получить все организации.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Получить все организации в конкретном здании.
     *
     * @param int $buildingId
     * @return Collection
     */
    public function getByBuildingID(int $buildingId): Collection;

    /**
     * Поиск организаций по названию.
     *
     * @param string $name
     * @return Collection
     */
    public function searchByName(string $name): Collection;
}
