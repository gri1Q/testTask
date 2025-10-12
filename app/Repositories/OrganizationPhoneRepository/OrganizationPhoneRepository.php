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

    public function getPhonesByOrganizationIDs(array $organizationIds): array
    {
        $rows = OrganizationPhone::query()
            ->whereIn('organization_id', $organizationIds)
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $map[$row->organization_id][] = $row;
        }
        // гарантируем ключи для всех orgIds
        foreach ($organizationIds as $id) {
            $map[$id] = $map[$id] ?? [];
        }
        return $map;
    }

    public function searchByNumber(string $phone): Collection
    {
        return OrganizationPhone::query()
            ->where('phone', $phone)
            ->get();
    }
}
