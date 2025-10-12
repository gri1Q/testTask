<?php

namespace App\Repositories\ActivityRepository;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

interface ActivityRepositoryInterface
{
    /**
     * Создать новый вид деятельности.
     *
     * @param Activity $activity
     * @return void
     */
    public function create(Activity $activity): void;

    /**
     * Получить вид деятельности по ID.
     *
     * @param int $id
     * @return Activity
     */
    public function getByID(int $id): Activity;

    /**
     * Получить все виды деятельности.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Получить только корневые виды деятельности.
     *
     * @return Collection
     */
    public function getRootActivities(): Collection;

    /**
     * Получить дочерние виды деятельности для указанного родителя.
     *
     * @param int $parentId
     * @return Collection
     */
    public function getChildren(int $parentId): Collection;

    /**
     * Получить полное дерево видов деятельности (до 3 уровней).
     *
     * @return Collection
     */
    public function getTree(): Collection;
}
