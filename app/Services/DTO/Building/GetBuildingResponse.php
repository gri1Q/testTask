<?php

declare(strict_types=1);

namespace App\Services\DTO\Building;

/**
 * Ответ от сервиса с одним зданием.
 */
class GetBuildingResponse
{

    public function __construct(public BuildingItem $building)
    {
    }

    /**
     * Преобразование ответа к массиву.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'building' => $this->building->toArray(),
        ];
    }
}
