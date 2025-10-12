<?php

declare(strict_types=1);

namespace App\Services\DTO\Building;

/**
 * Ответ от сервиса со списком зданий.
 */
class ListBuildingsResponse
{

    public function __construct(public array $buildings)
    {
    }


    /**
     * Представление ответа в виде массива.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'buildings' => array_map(
                static fn(BuildingItem $item): array => $item->toArray(),
                $this->buildings,
            ),
        ];
    }
}
