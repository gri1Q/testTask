<?php

declare(strict_types=1);

namespace App\Services\DTO\Organization;

class ListOrganizationsInBuildingResult
{

    /**
     * @param array $organizations
     */
    public function __construct(
        public array $organizations
    ) {
    }
}
