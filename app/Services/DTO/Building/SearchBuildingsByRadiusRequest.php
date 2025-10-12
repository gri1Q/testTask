<?php

declare(strict_types=1);

namespace App\Services\DTO\Building;

/**
 * Параметры поиска зданий по радиусу.
 */
class SearchBuildingsByRadiusRequest
{
    /**
     * @param float $latitude Центральная широта
     * @param float $longitude Центральная долгота
     * @param float $radiusMeters Радиус в метрах
     */
    public function __construct(
        public float $latitude,
        public float $longitude,
        public float $radiusMeters,
    ) {
    }
}
