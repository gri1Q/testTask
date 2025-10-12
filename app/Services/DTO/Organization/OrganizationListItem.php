<?php

declare(strict_types=1);

namespace App\Services\DTO\Organization;


use App\Services\DTO\Building\BuildingItem;

/**
 * Короткая карточка организации для списков.
 */
class OrganizationListItem
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
     * @param float|null $distanceKm
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
        public ?float $distanceKm = null,
    ) {}
}
