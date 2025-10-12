<?php

declare(strict_types=1);

namespace App\Repositories\OrganizationPhoneRepository;

use App\Models\OrganizationPhone;
use Illuminate\Database\Eloquent\Collection;

class OrganizationPhoneRepository implements OrganizationPhoneRepositoryInterface
{

    /**
     * Получить все телефоны организации.
     *
     * @param int $organizationID
     * @return Collection
     */
    public function allByOrganization(int $organizationID): Collection
    {
        return OrganizationPhone::query()
            ->where('organization_id', $organizationID)
            ->get();
    }

    /**
     * Получить все телефоны по IDs организаций.
     *
     * @param array $organizationIDs
     * @return Collection
     */
    public function getPhonesByOrganizationIDs(array $organizationIDs): Collection
    {
        return OrganizationPhone::query()
            ->whereIn('organization_id', $organizationIDs)
            ->get();
    }
}
