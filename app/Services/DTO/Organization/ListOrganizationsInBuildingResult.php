<?php

namespace App\Services\DTO\Organization;

class ListOrganizationsInBuildingResult
{
    /**
     * @param OrganizationItem[] $organizations
     */
    public function __construct(
        public array $organizations
    ) {
    }
}
