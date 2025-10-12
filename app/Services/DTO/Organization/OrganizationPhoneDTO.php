<?php

declare(strict_types=1);

namespace App\Services\DTO\Organization;

class OrganizationPhoneDTO
{
    public function __construct(
        public string $phone,
    ) {}
}
