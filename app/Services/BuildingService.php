<?php

namespace App\Services;

use App\Repositories\BuildingRepository\BuildingRepositoryInterface;

class BuildingService
{
    public function __construct(private BuildingRepositoryInterface $buildingRepository)
    {
    }
}
