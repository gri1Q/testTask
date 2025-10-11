<?php

namespace App\Repositories\OrganizationPhoneRepository;

use App\Models\OrganizationPhone;
use Illuminate\Database\Eloquent\Collection;

class OrganizationPhoneRepository
{
    /**
     * Создать телефон для организации.
     *
     * @param int $organizationId
     * @param string $number
     * @return OrganizationPhone
     */
    public function create(int $organizationId, string $number): OrganizationPhone
    {
        return OrganizationPhone::query()->create([
            'organization_id' => $organizationId,
            'number' => $number,
        ]);
    }

    /**
     * Получить все телефоны организации.
     *
     * @param int $organizationId
     * @return Collection
     */
    public function allByOrganization(int $organizationId): Collection
    {
        return OrganizationPhone::query()
            ->where('organization_id', $organizationId)
            ->get();
    }

    /**
     * Найти телефоны по номеру.
     *
     * @param string $phone
     * @return Collection
     */
    public function searchByNumber(string $phone): Collection
    {
        return OrganizationPhone::query()
            ->where('phone', $phone)
            ->get();
    }
}
