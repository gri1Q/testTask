<?php

namespace App\Repositories\ActivityRepository;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

interface ActivityRepositoryInterface
{
    /**
     * Найти сущность по ID.
     *
     * @param int $id
     * @return Activity
     */
    public function findOrFail(int $id): Activity;

    /**
     * Получить коллекцию по массиву ID (пустой массив => пустая коллекция).
     */
    public function findMany(array $ids): Collection;

    /**
     * Вернуть дочерние записи для указанного родителя.
     *
     * @param int $parentId
     * @return Collection
     */
    public function getChildren(int $parentId): Collection;

    /**
     *  Вернуть ID всех детей для набора родительских ID.
     *  Удобно для уровневого обхода.
     *
     * @param array $parentIds
     * @return array|int[]
     */
    public function getChildrenIdsOf(array $parentIds): array;


    /**
     * Получить только корневые записи.
     *
     * @return Collection
     */
    public function getRoots(): Collection;
}
