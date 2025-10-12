<?php

namespace App\Services;

use App\Repositories\ActivityRepository\ActivityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ActivityService
{
    public function __construct(private ActivityRepositoryInterface $activityRepository)
    {
    }

    public function first(int $id)
    {
        return $this->activityRepository->first($id);
    }

    /** @return list<int> */
    public function getDescendantIDs(int $activityId): array
    {
        return $this->activityRepository->getDescendantIDs($activityId);
    }

    /** @return \Illuminate\Support\Collection<int, mixed> keyBy('id') обычно в сервисе */
    public function getByIDs(array $ids): Collection
    {
        return $this->activityRepository->getByIDs($ids);
    }

    /** @return array<int, list<int>> orgId => [activityId,...] */
    public function getActivityIDsByOrganizationIDs(array $organizationIds): array
    {
        return $this->activityRepository->getActivityIDsByOrganizationIDs($organizationIds);
    }
}
