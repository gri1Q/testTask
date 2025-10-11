<?php

namespace App\Services;

use App\Repositories\ActivityRepository\ActivityRepositoryInterface;

class ActivityService
{
    public function __construct(private ActivityRepositoryInterface $activityRepository)
    {
    }
}
