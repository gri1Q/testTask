<?php

namespace App\Repositories\OrganizationPhoneRepository;

use App\Models\OrganizationPhone;
use Illuminate\Database\Eloquent\Collection;

class OrganizationPhoneRepository implements OrganizationPhoneRepositoryInterface
{
    public function create(int $organizationId, string $number): OrganizationPhone
    {
        return OrganizationPhone::query()->create([
            'organization_id' => $organizationId,
            'phone' => $number,
        ]);
    }

    public function allByOrganization(int $organizationId): Collection
    {
        return OrganizationPhone::query()
            ->where('organization_id', $organizationId)
            ->get();
    }

    /**
     * @param array $organizationIds
     * @return Collection
     */
    public function getPhonesByOrganizationIDs(array $organizationIds): Collection
    {
        return OrganizationPhone::query()
            ->whereIn('organization_id', $organizationIds)
            ->get();

    }

    public function searchByNumber(string $phone): Collection
    {
        return OrganizationPhone::query()
            ->where('phone', $phone)
            ->get();
    }
}
