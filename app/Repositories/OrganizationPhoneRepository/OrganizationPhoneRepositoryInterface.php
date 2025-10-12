<?php

declare(strict_types=1);

namespace App\Repositories\OrganizationPhoneRepository;

use Illuminate\Database\Eloquent\Collection;

interface OrganizationPhoneRepositoryInterface
{


    /**
     * Получить все телефоны организации.
     *
     * @param int $organizationID
     * @return Collection
     */
    public function allByOrganization(int $organizationID): Collection;

    /**
     * Получить все телефоны по IDs организаций.
     *
     * @param array $organizationIDs
     * @return Collection
     */
    public function getPhonesByOrganizationIDs(array $organizationIDs): Collection;
}
