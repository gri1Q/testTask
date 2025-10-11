<?php

namespace App\Repositories\ActivityRepository;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class ActivityRepository implements ActivityRepositoryInterface
{
    /**
     * Создать новый вид деятельности.
     *
     * @param Activity $activity
     * @return void
     */
    public function create(Activity $activity): void
    {
        $activity->save();
    }

    /**
     * Получить вид деятельности по ID.
     *
     * @param int $id
     * @return Activity
     */
    public function getByID(int $id): Activity
    {
        return Activity::query()->findOrFail($id);
    }

    /**
     * Получить все виды деятельности.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Activity::all();
    }

    /**
     * Получить только корневые виды деятельности.
     *
     * @return Collection
     */
    public function getRootActivities(): Collection
    {
        return Activity::query()
            ->whereNull('parent_id')
            ->get();
    }

    /**
     * Получить дочерние виды деятельности для указанного родителя.
     *
     * @param int $parentId
     * @return Collection
     */
    public function getChildren(int $parentId): Collection
    {
        return Activity::query()
            ->where('parent_id', $parentId)
            ->get();
    }


    /**
     * Получить полное дерево видов деятельности (до 3 уровней).
     *
     * @return Collection
     */
    public function getTree(): Collection
    {
        return Activity::query()
            ->where('level', '<=', 3)
            ->orderBy('level')
            ->orderBy('id')
            ->get();
    }

}
