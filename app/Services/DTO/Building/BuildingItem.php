<?php

declare(strict_types=1);

namespace App\Services\DTO\Building;

use Illuminate\Support\Carbon;

/**
 * DTO здания для сценариев сервиса.
 */
class BuildingItem
{
    /**
     * @param int $buildingID
     * @param string $address
     * @param float $latitude
     * @param float $longitude
     * @param Carbon $createdAt
     * @param Carbon $updatedAt
     */
    public function __construct(
        public int $buildingID,
        public string $address,
        public float $latitude,
        public float $longitude,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {
    }


    /**
     * Представление DTO в виде массива.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->buildingID,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
