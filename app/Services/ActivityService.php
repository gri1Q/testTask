<?php

namespace App\Services;

use App\Repositories\ActivityRepository\ActivityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ActivityService
{
    public function __construct(private ActivityRepositoryInterface $activityRepository)
    {
    }

    /**
     * Поулчить по IDs.
     *
     * @param array $IDs
     * @return Collection
     */
    public function getByIDs(array $IDs): Collection
    {
        return $this->activityRepository->getByIDs($IDs);
    }
}
