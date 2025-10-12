<?php

declare(strict_types=1);

namespace App\Services\DTO\Organization;

use App\Services\DTO\Activity\ActivityItem;
use DateTimeInterface;

class OrganizationItem
{
    /**
     * @param int $organizationID
     * @param string $name
     * @param string|null $description
     * @param string|null $email
     * @param string[]|null $phones Список телефонов
     * @param ActivityItem[]|null $activities Список видов деятельности
     * @param int $buildingID
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        public int $organizationID,
        public string $name,
        public ?string $description,
        public ?string $email,
        public ?array $phones,
        public ?array $activities,
        public int $buildingID,
        public ?DateTimeInterface $createdAt,
        public ?DateTimeInterface $updatedAt,
    ) {
    }
}
