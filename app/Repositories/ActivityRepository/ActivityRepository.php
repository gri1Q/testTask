<?php

namespace App\Repositories\ActivityRepository;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

/**
 * Репозиторий для сущности Activity.
 */
class ActivityRepository implements ActivityRepositoryInterface
{

    /**
     * Получить вид деятельности по ID.
     *
     * @param int $activityID
     * @return Activity
     */
    public function first(int $activityID): Activity
    {
        return Activity::query()->findOrFail($activityID);
    }


    /**
     * Получить коллекцию видов деятельности по их ID.
     *
     * @param array $activityIDs
     * @return Collection
     */
    public function getByIDs(array $activityIDs): Collection
    {
        if ($activityIDs === []) {
            return new Collection();
        }

        return Activity::query()->whereIn('id', $activityIDs)->get();
    }


    /**
     * Получить идентификаторы всех дочерних видов деятельности.
     *
     * @param int $activityID
     * @return array
     */
    public function getDescendantIDs(int $activityID): array
    {
        $descendantIDs = [];
        $currentLevel = [$activityID];

        while ($currentLevel !== []) {
            $children = Activity::query()
                ->whereIn('parent_id', $currentLevel)
                ->pluck('id')
                ->all();

            if ($children === []) {
                break;
            }

            $descendantIDs = array_merge($descendantIDs, $children);
            $currentLevel = $children;
        }

        return $descendantIDs;
    }

}
