<?php

namespace App\Services;


use App\Repositories\OrganizationPhoneRepository\OrganizationPhoneRepositoryInterface;
use App\Repositories\OrganizationRepository\OrganizationRepositoryInterface;

class OrganizationService
{
    public function __construct(
        private OrganizationRepositoryInterface $organizationRepository,
        private OrganizationPhoneRepositoryInterface $organizationPhoneRepository
    ) {
    }
}
