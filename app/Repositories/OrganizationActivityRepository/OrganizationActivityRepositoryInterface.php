<?php

declare(strict_types=1);

namespace App\Repositories\OrganizationActivityRepository;

use Illuminate\Database\Eloquent\Collection;

interface OrganizationActivityRepositoryInterface
{
    /**
     * Получить связующую таблицу по IDs организации.
     *
     * @param array $organizationIDs
     * @return Collection
     */
    public function getOrganizationActivityByOrganizationIDs(array $organizationIDs): Collection;
}
