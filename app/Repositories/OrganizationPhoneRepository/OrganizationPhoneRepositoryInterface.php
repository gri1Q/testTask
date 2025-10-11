<?php

namespace App\Repositories\OrganizationPhoneRepository;

use App\Models\OrganizationPhone;
use Illuminate\Database\Eloquent\Collection;

interface OrganizationPhoneRepositoryInterface
{
    /**
     * Создать телефон для организации.
     *
     * @param int $organizationId
     * @param string $number
     * @return OrganizationPhone
     */
    public function create(int $organizationId, string $number): OrganizationPhone;

    /**
     * Получить все телефоны организации.
     *
     * @param int $organizationId
     * @return Collection
     */
    public function allByOrganization(int $organizationId): Collection;

    /**
     * Найти телефоны по номеру.
     *
     * @param string $phone
     * @return Collection
     */
    public function searchByNumber(string $phone): Collection;
}
