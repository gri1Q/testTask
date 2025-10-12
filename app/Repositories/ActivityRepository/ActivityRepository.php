<?php

declare(strict_types=1);

namespace App\Repositories\ActivityRepository;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class ActivityRepository implements ActivityRepositoryInterface
{
    /**
     * Поулчить по IDs.
     *
     * @param array $ids
     * @return Collection
     */
    public function getByIDs(array $ids): Collection
    {
        return Activity::query()->whereIn('id', $ids)->get();
    }
}
