<?php

declare(strict_types=1);

namespace App\Services\DTO\Organization;


use App\Services\DTO\Building\BuildingItem;

/**
 * Полная карточка организации.
 */
class OrganizationDTO
{
    /**
     * @param int $id
     * @param string $name
     * @param string $address
     * @param float $latitude
     * @param float $longitude
     * @param BuildingItem $building
     * @param array $activities
     * @param array $phones
     * @param string|null $description
     * @param string|null $email
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $address,
        public float $latitude,
        public float $longitude,
        public BuildingItem $building,
        public array $activities,
        public array $phones,
        public ?string $description,
        public ?string $email,
    ) {
    }
}
